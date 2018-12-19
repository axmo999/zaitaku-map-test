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
    attr_accessor :facility_id, :question_cd, :answer_cd, :answer_conent
end

def getFacilityId(facilityName)
    if facilityName.nil?
        return nil
    end
    Dotenv.load "../../.env"
    hostname = ENV["DB_HOST"]
    username = ENV["DB_USERNAME"]
    password = ENV["DB_PASSWORD"]
    database = ENV["DB_DATABASE"]
    client = Mysql2::Client.new(:host => hostname, :username => username, :password => password, :database => database)
    # 文字コードをUTF8に設定
    client.query("set character set utf8")
    # DBに問い合わせ
    statement = client.prepare("SELECT id FROM facilities where facility_name = ?")
    result = statement.execute(facilityName).first
    client.close
    return result["id"]
end

def getQuestionCd(questionName)
    if questionName.nil?
        return nil
    end
    Dotenv.load "../../.env"
    hostname = ENV["DB_HOST"]
    username = ENV["DB_USERNAME"]
    password = ENV["DB_PASSWORD"]
    database = ENV["DB_DATABASE"]
    client = Mysql2::Client.new(:host => hostname, :username => username, :password => password, :database => database)
    # 文字コードをUTF8に設定
    client.query("set character set utf8")
    # DBに問い合わせ
    statement = client.prepare("SELECT question_cd FROM m_question_cds where question_content = ?")
    result = statement.execute(questionName).first
    client.close
    return result["question_cd"].to_s
end

def getAttribute(contents, questionName, facility_id)
    if contents.nil?
        return nil
    end
    row = []
    questionCd = getQuestionCd(questionName)
    Dotenv.load "../../.env"
    hostname = ENV["DB_HOST"]
    username = ENV["DB_USERNAME"]
    password = ENV["DB_PASSWORD"]
    database = ENV["DB_DATABASE"]
    client = Mysql2::Client.new(:host => hostname, :username => username, :password => password, :database => database)
    # 文字コードをUTF8に設定
    client.query("set character set utf8")

    contents_split = contents.split("|")
    p contents_split
    contents_split.each do |content|
        p content
        statement = client.prepare("SELECT answer_cd FROM m_answer_cds where answer_content = ?")
        result = statement.execute(content).first
        row.push([facility_id, questionCd, result["answer_cd"]])
    end
    client.close
    return row
end

attibute_data = CSV.generate do |csv|

    header = [
                    "facility_id",
                    "question_cd",
                    "answer_cd",
                    "answer_conent",
                    "created_at",
                    "updated_at"
                ]
    csv << header

    CSV.foreach('WordPress.csv', headers: true) do |data|
        facility_id = getFacilityId(data["Title"])
        if !data["受け入れ可"].nil?
            row = []
            row = getAttribute(data["受け入れ可"], "受け入れ可", facility_id)
            p row
        end

    end

end
