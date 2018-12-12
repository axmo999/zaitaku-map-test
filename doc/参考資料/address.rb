# encoding: utf-8
require 'csv'
require 'net/http'
require 'openssl'
require 'uri'
require 'json'
require 'logger'
require 'nkf'

class GetAddress

    def initialize()
        @data = {}
    end

    def lookup(post_code)
        post3 = post_code.slice(0,3)
        post5 = post_code.slice(3,5)
        https = Net::HTTP.new("madefor.github.io", 443)
        https.open_timeout = 5
        https.read_timeout = 5
        https.use_ssl = true
        begin
            response = nil
            path = "/postal-code-api/api/v1/" + post3 + "/" + post5 + ".json"
            https.start do
                response = https.get(path)
                if response.code.to_i.eql?(200)
                    @data = JSON.parse(response.body)
                else
                    @data ={}
                end
            end
            rescue JSON::ParserError => e
            p e
        end
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
class JigyosyoPostCode
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

index = PostCodeIndex.new('47OKINAW.CSV')
jigyosyo = JigyosyoPostCode.new('JIGYOSYO.CSV')
getPostal = GetPostalCode.new

address2 = CSV.generate do |csv|
    header = ["Title", "郵便番号", "都道府県名", "市町村名", "住所", "マップ_lat", "マップ_lng", "電話番号", "FAX番号", "代表者", "ホームページ"]
    csv << header
    CSV.foreach('address.csv', headers: true) do |data|

        convertAddress = ""
        juusyo = ""

        if !data["郵便番号"].nil?
            if !index.lookup(data["郵便番号"]).nil?
                convertPostalCode = index.lookup(data["郵便番号"])
            elsif !jigyosyo.lookup(data["郵便番号"]).nil?
                convertPostalCode = jigyosyo.lookup(data["郵便番号"])
            else
                if !data["住所"].nil?
                    data["郵便番号"] = getPostal.lookup(data["住所"])
                    if !index.lookup(data["郵便番号"]).nil?
                        convertPostalCode = index.lookup(data["郵便番号"])
                    elsif !jigyosyo.lookup(data["郵便番号"]).nil?
                        convertPostalCode = jigyosyo.lookup(data["郵便番号"])
                    end
                end
            end

            if !convertPostalCode.nil?
                convertAddress = convertPostalCode.dig(:prefecture)
            end

            if !data["住所"].nil?
                deleteAddress = convertAddress.delete('/国頭郡|中頭郡|島尻郡/')
                juusyo = data["住所"].delete('/国頭郡|中頭郡|島尻郡/').sub(deleteAddress, "")
            end

        elsif !data["住所"].nil?
            data["郵便番号"] = getPostal.lookup(data["住所"])
            if !index.lookup(data["郵便番号"]).nil?
                convertPostalCode = index.lookup(data["郵便番号"])
            elsif !jigyosyo.lookup(data["郵便番号"]).nil?
                convertPostalCode = jigyosyo.lookup(data["郵便番号"])
            end

            if !convertPostalCode.nil?
                convertAddress = convertPostalCode.dig(:prefecture)
            end

            if !data["住所"].nil?
                deleteAddress = convertAddress.delete('/国頭郡|中頭郡|島尻郡/')
                juusyo = data["住所"].delete('/国頭郡|中頭郡|島尻郡/').sub(deleteAddress, "")
            end
        end

        if convertAddress.length==0
            if !data["住所"].nil?
                data["住所"] = data["住所"].delete('/字/').strip
                data["郵便番号"] = getPostal.lookup(data["住所"])
                if data["郵便番号"].nil?
                    data["郵便番号"] = getPostal.lookup("国頭郡" + data["住所"])
                end
                if data["郵便番号"].nil?
                    data["郵便番号"] = getPostal.lookup("中頭郡" + data["住所"])
                end
                if data["郵便番号"].nil?
                    data["郵便番号"] = getPostal.lookup("島尻郡" + data["住所"])
                end

                if !index.lookup(data["郵便番号"]).nil?
                    convertPostalCode = index.lookup(data["郵便番号"])
                elsif !jigyosyo.lookup(data["郵便番号"]).nil?
                    convertPostalCode = jigyosyo.lookup(data["郵便番号"])
                end

                if !convertPostalCode.nil?
                    convertAddress = convertPostalCode.dig(:prefecture)
                end

                if !data["住所"].nil?
                    deleteAddress = convertAddress.delete('/国頭郡|中頭郡|島尻郡/')
                    juusyo = data["住所"].delete('/国頭郡|中頭郡|島尻郡/').sub(deleteAddress, "").sub('/字/', "")
                end
            end
        end

=begin
        juusyo = data["住所"]
        if data["郵便番号"].nil?
            getAddress2 = data["市町村名"]
        else
            getAddress2 = index.lookup(data["郵便番号"])
            if getAddress2.nil?
                getAddress2 = jigyosyo.lookup(data["郵便番号"])
                if !getAddress2.nil?
                    getAddress2 = getAddress2.dig(:prefecture)
                    if !data["住所"].nil?
                    deleteAddress = getAddress2.delete('/国頭郡|中頭郡|島尻郡/')
                    juusyo = data["住所"].delete('/国頭郡|中頭郡|島尻郡/').delete(deleteAddress)
                    #p juusyo
                    end
                end
            else
                getAddress2 = getAddress2.dig(:prefecture)
                if !data["住所"].nil?
                    deleteAddress = getAddress2.delete('/国頭郡|中頭郡|島尻郡/')
                    juusyo = data["住所"].delete('/国頭郡|中頭郡|島尻郡/').delete(deleteAddress)
                    #p juusyo
                end

            end
        end
=end

        if !data["電話番号"].nil?
            data["電話番号"] = NKF.nkf('-w -Z4', data["電話番号"])
            data["電話番号"] = data["電話番号"].sub("/\(/", "-").sub("/\)/", "-")
        end

        if !data["FAX番号"].nil?
            data["FAX番号"] = NKF.nkf('-w -Z4', data["FAX番号"])
            data["FAX番号"] = data["FAX番号"].sub("/\(/", "-").sub("/\)/", "-")
        end

        insert = [
            data["Title"],
            data["郵便番号"],
            data["都道府県名 "],
            convertAddress,
            juusyo,
            data["マップ_lat"],
            data["マップ_lng"],
            data["電話番号"],
            data["FAX番号"],
            data["代表者"],
            data["ホームページ"]
        ]
        csv << insert
        #p insert
    end
end



File.open("address2.csv", "a") do |file|
    file.write(address2)
end
