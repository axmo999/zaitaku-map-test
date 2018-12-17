# encoding: utf-8
require 'csv'
require 'net/http'
require 'openssl'
require 'uri'
require 'json'
require 'logger'
require 'nkf'
require 'date'
require 'mysql2'

class Entity
    attr_accessor :facility_name, :home_care, :facility_type_id, :postal_code, :prefecture_name, :city_name, :address, :latitude, :longitude, :telphone, :fax, :representative, :homepage, :available_time_mon, :available_time_tue, :available_time_wed, :available_time_thu, :available_time_fri, :available_time_sat, :available_time_sun, :person, :correspondence_dept, :correspondence_time, :open_24hours, :foreign_language, :related_facilities, :options, :note, :publish, :user_id, :created_at, :updated_at
end

def getFacilityType(type_name)
    if type_name.nil?
        return nil
    end
    getType = type_name.split("|")
    client = Mysql2::Client.new(:host => "localhost", :username => "www-user", :password => "www-user", :database => "zaitakumap")
    # 文字コードをUTF8に設定
    client.query("set character set utf8")
    # DBに問い合わせ
    statement = client.prepare("SELECT id FROM m_facility_types where facility_type_name = ?")
    result = statement.execute(getType[0]).first
    client.close
    return result["id"]
end

def getHomeCare(facility_type)
    if !facility_type.nil? && facility_type.include?("（在宅）")
        return 1
    end
    return nil
end

def getPublish(status)
    if status=="publish"
        return 1
    end
    return nil
end

def getAuther()
end

# 郵便番号から住所取得
class PostCodeIndex
  def initialize(file)
    @data = {}

    CSV.foreach(file, encoding: 'Shift_JIS:UTF-8') do |row|
      post_code, city, prefecture, street = row[2], row[6], row[7], row[8]
      @data[post_code] = {
        post_code: post_code,
        prefecture: prefecture,
        city: city,
        street: clean_street(street)
      }
    end
  end

  def lookup(post_code)
    @data[clean_post_code(post_code)]
  end

  private

  def clean_street(input)
    return '' if input == '以下に掲載がない場合'
    input.sub(/（.*$/, '')
  end

  def clean_post_code(input)
    input.to_s.tr('０-９', '0-9').gsub(/[^0-9]/, '')
  end

end

# 郵便番号から住所取得（事業所）
class OfficePostCode
  def initialize(file2)
    @data = {}

    CSV.foreach(file2, encoding: 'CP932:UTF-8') do |row|
      post_code, city, prefecture, street = row[7], row[3], row[4], row[5] + row[6]
      @data[post_code] = {
        post_code: post_code,
        prefecture: prefecture,
        city: city,
        street: clean_street(street)
      }
    end
  end

  def lookup(post_code)
    @data[clean_post_code(post_code)]
  end

  private

  def clean_street(input)
    return '' if input == '以下に掲載がない場合'
    input.sub(/（.*$/, '')
  end

  def clean_post_code(input)
    input.to_s.tr('０-９', '0-9').gsub(/[^0-9]/, '')
  end

end

# 住所から郵便番号取得
class GetPostalCode

    def initialize()
        @data = {}
    end

    def lookup(address)
        https = Net::HTTP.new("zipcoda.net", 443)
        https.open_timeout = 5
        https.read_timeout = 5
        https.use_ssl = true
        begin
            response = nil
            path = "/api/?address=沖縄県" + address
            https.start do
                response = https.get(path)
                if response.code.to_i.eql?(200)
                    if JSON.parse(response.body)["status"].eql?("success")
                        @data = JSON.parse(response.body).dig("items", 0, "zipcode")
                    end
                else
                    #@data ={}
                end
            end
            rescue JSON::ParserError => e
            p e
        end
    end

end

def getPostalCode(postalcode, address)
    postCodeIndex = PostCodeIndex.new('47OKINAW.CSV')
    officeIndex = OfficePostCode.new('JIGYOSYO.CSV')

    if !postalcode.nil?
        postalcode_num = postalcode.gsub(/[^\d]/, "")

        if !postCodeIndex.lookup(postalcode_num).nil?
            convertPostalCode = postCodeIndex.lookup(postalcode_num)
        elsif !officeIndex.lookup(postalcode_num).nil?
            convertPostalCode = officeIndex.lookup(postalcode_num)
        end

        if !convertPostalCode.nil?
            return convertPostalCode
        end
        return nil
    end
    return nil
end



CSV.open('test.csv', 'w', :force_quotes => true) do |row|

    header = [
                    "facility_name",
                    "home_care",
                    "facility_type_id",
                    "postal_code",
                    "prefecture_name",
                    "city_name",
                    "address",
                    "latitude",
                    "longitude",
                    "telphone",
                    "fax",
                    "representative",
                    "homepage",
                    "available_time_mon",
                    "available_time_tue",
                    "available_time_wed",
                    "available_time_thu",
                    "available_time_fri",
                    "available_time_sat",
                    "available_time_sun",
                    "person",
                    "correspondence_dept",
                    "correspondence_time",
                    "open_24hours",
                    "foreign_language",
                    "related_facilities",
                    "options",
                    "note",
                    "publish",
                    "user_id",
                    "created_at",
                    "updated_at"
                ]
    row << header

    CSV.foreach('WordPress.csv', headers: true) do |data|
        entity = Entity.new
        entity.facility_name = data["Title"]

        entity.facility_type_id = getFacilityType(data["category"])
        entity.home_care = getHomeCare(data["category"])

        entity.telphone = data["電話番号"]
        entity.fax = data["FAX番号"]
        entity.representative = data["代表者"]
        entity.homepage = data["ホームページ"]
        entity.available_time_mon = data["対応可能時間（月曜日）"]
        entity.available_time_tue = data["対応可能時間（火曜日）"]
        entity.available_time_wed = data["対応可能時間（水曜日）"]
        entity.available_time_thu = data["対応可能時間（木曜日）"]
        entity.available_time_fri = data["対応可能時間（金曜日）"]
        entity.available_time_sat = data["対応可能時間（土曜日）"]
        entity.available_time_sun = data["対応可能時間（日・祝日）"]
        entity.person = data["窓口担当者"]
        entity.correspondence_dept = data["窓口対応部署"]
        entity.correspondence_time = data["窓口対応時間"]
        entity.open_24hours = data["24時間対応"]
        entity.foreign_language = data["外国語対応"]
        entity.related_facilities = data["併設・関連施設"]
        entity.options = data["オプション・事業所のアピール等"]
        entity.note = data["特記"]

        entity.publish = getPublish(data["Status"])
        entity.created_at = Date.parse(data["Date"].to_s).to_s
        entity.updated_at = Date.parse(data["Post Modified Date"].to_s).to_s

        row << [
            entity.facility_name,
            entity.home_care,
            entity.facility_type_id,
            entity.postal_code,
            entity.prefecture_name,
            entity.city_name,
            entity.address,
            entity.latitude,
            entity.longitude,
            entity.telphone,
            entity.fax,
            entity.representative,
            entity.homepage,
            entity.available_time_mon,
            entity.available_time_tue,
            entity.available_time_wed,
            entity.available_time_thu,
            entity.available_time_fri,
            entity.available_time_sat,
            entity.available_time_sun,
            entity.person,
            entity.correspondence_dept,
            entity.correspondence_time,
            entity.open_24hours,
            entity.foreign_language,
            entity.related_facilities,
            entity.options,
            entity.note,
            entity.publish,
            entity.user_id,
            entity.created_at,
            entity.updated_at
        ]
    end

end


