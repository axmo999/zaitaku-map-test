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
require 'dotenv'

class Entity
    attr_accessor :facility_name, :home_care, :facility_type_id, :postal_code, :prefecture_name, :city_name, :address, :latitude, :longitude, :telphone, :fax, :representative, :homepage, :available_time_mon, :available_time_tue, :available_time_wed, :available_time_thu, :available_time_fri, :available_time_sat, :available_time_sun, :person, :correspondence_dept, :correspondence_time, :open_24hours, :foreign_language, :related_facilities, :options, :note, :publish, :user_id, :created_at, :updated_at
end

def getFacilityType(type_name)
    if type_name.nil?
        return nil
    end
    getType = type_name.split("|")
    Dotenv.load "../../.env"
    hostname = ENV["DB_HOST"]
    username = ENV["DB_USERNAME"]
    password = ENV["DB_PASSWORD"]
    database = ENV["DB_DATABASE"]
    client = Mysql2::Client.new(:host => hostname, :username => username, :password => password, :database => database)
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
      post_code, city, prefecture, street = row[2], row[7], row[6], row[8]
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
    index = PostCodeIndex.new('47OKINAW.CSV')
    jigyosyo = OfficePostCode.new('JIGYOSYO.CSV')
    getPostal = GetPostalCode.new
    convertAddress = ""
    juusyo = ""

    if !postalcode.nil?
        postalcode_num = postalcode.gsub(/[^\d]/, "")
        if !index.lookup(postalcode_num).nil?
            convertPostalCode = index.lookup(postalcode_num)
        elsif !jigyosyo.lookup(postalcode_num).nil?
            convertPostalCode = jigyosyo.lookup(postalcode_num)
        else
            if !address.nil?
                postalcode = getPostal.lookup(postalcode)
                if !index.lookup(postalcode).nil?
                    convertPostalCode = index.lookup(postalcode)
                elsif !jigyosyo.lookup(postalcode).nil?
                    convertPostalCode = jigyosyo.lookup(postalcode)
                end
            end
        end

        if !convertPostalCode.nil?
            convertAddress = convertPostalCode.dig(:prefecture)
        end

        if !address.nil?
                deleteAddress = convertAddress.delete('/国頭郡|中頭郡|島尻郡/')
                juusyo = address.delete('/国頭郡|中頭郡|島尻郡/').sub(deleteAddress, "")
        end

    elsif !address.nil?
        postalcode = getPostal.lookup(address)
        if !index.lookup(postalcode).nil?
            convertPostalCode = index.lookup(postalcode)
        elsif !jigyosyo.lookup(postalcode).nil?
            convertPostalCode = jigyosyo.lookup(postalcode)
        end

        if !convertPostalCode.nil?
            convertAddress = convertPostalCode.dig(:prefecture)
        end

        if !address.nil?
            deleteAddress = convertAddress.delete('/国頭郡|中頭郡|島尻郡/')
            juusyo = address.delete('/国頭郡|中頭郡|島尻郡/').sub(deleteAddress, "")
        end
    end

    if convertAddress.length==0
        if !address.nil?
            address = address.delete('/字/').strip
            postalcode = getPostal.lookup(address)
            if postalcode.nil?
                postalcode = getPostal.lookup("国頭郡" + address)
            end
            if postalcode.nil?
                postalcode = getPostal.lookup("中頭郡" + address)
            end
            if postalcode.nil?
                postalcode = getPostal.lookup("島尻郡" + address)
            end

            if !index.lookup(postalcode).nil?
                convertPostalCode = index.lookup(postalcode)
            elsif !jigyosyo.lookup(postalcode).nil?
                convertPostalCode = jigyosyo.lookup(postalcode)
            end

            if !convertPostalCode.nil?
                convertAddress = convertPostalCode.dig(:prefecture)
            end

            if !address.nil?
                deleteAddress = convertAddress.delete('/国頭郡|中頭郡|島尻郡/')
                juusyo = address.delete('/国頭郡|中頭郡|島尻郡/').sub(deleteAddress, "")
            end
        end
    end

    return convertPostalCode.dig(:post_code), convertPostalCode.dig(:prefecture), convertPostalCode.dig(:city), juusyo

end

def getPhoneNum(phone)
    if !phone.nil?
        phone = NKF.nkf('-w -Z4', phone)
        phone = phone.sub("/\(/", "-").sub("/\)/", "-")
    end
    return phone
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

        #entity.postal_code, entity.prefecture_name, entity.city_name, entity.address = getPostalCode(data["郵便番号"], data["住所"])

        entity.telphone = getPhoneNum(data["電話番号"])
        entity.fax = getPhoneNum(data["FAX番号"])
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


