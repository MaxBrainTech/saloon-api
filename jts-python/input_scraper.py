
import selenium
import datetime
import time 
import json
import requests
from selenium.common.exceptions import NoSuchElementException        
from selenium import webdriver
from selenium.webdriver.firefox.options import Options
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.common.action_chains import ActionChains
import mysql.connector
from mysql.connector import errorcode

def getSalonboarUser():
    try:
        mySQLConnection = mysql.connector.connect(
        	host='34.85.64.241',
        	database='jtsboard_new',
        	user='jts',
        	password='Jts5678?',
        	use_pure = True
        	)
        cursor = mySQLConnection.cursor(prepared=True)
        sql_select_query = """select id, sb_username, sb_password from users where sb_username != '' and sb_password != ''"""
        cursor.execute(sql_select_query)
        record = cursor.fetchall()        
        userRecords = []       
        for row in record:
        	userArray = []
        	userArray.insert(0,row[0])
        	userArray.insert(1,row[1])
        	userArray.insert(2,row[2])
        	userRecords.append(userArray)
        	# print(userArray)
    except mysql.connector.Error as error:
        print("Failed to get record from database: {}".format(error))
    finally:
        # closing database connection.
        if (mySQLConnection.is_connected()):
            cursor.close()
            mySQLConnection.close()
            # print("connection is closed")
    # print(userRecords)
    return userRecords
userRecords = getSalonboarUser()
	for x in userRecords:

		url = "https://salonboard.com/login/"
		reservation_detail_ext_url = "https://salonboard.com/KLP/reserve/ext/extReserveDetail/?reserveId="
		reservation_detail_net_url = "https://salonboard.com/KLP/reserve/net/reserveDetail/?reserveId="
		user_id = x[0]
		username = x[1]
		password = x[2]

		options = Options()
		# options.add_argument('-headless')
		driver = webdriver.Firefox()

		if __name__ == "__main__":
			driver.get(url)
			uname = driver.find_element_by_name("userId")# ← find by element name
			uname.send_keys(username) # ← enters the username in textbox
			passw = driver.find_element_by_name("password")
			passw.send_keys(password)  #← enters the password in textbox

			# Find the submit button using class name and click on it.
			submit_button = driver.find_element_by_class_name("input_area_btn_01").click()

			WebDriverWait(driver, 100).until( lambda driver: driver.find_element_by_xpath('//*[@id="globalNavi"]/ul[2]/li[1]/a/img'))
			time.sleep(2)
			driver.find_element_by_xpath('//*[@id="globalNavi"]/ul[2]/li[1]/a/img').click()
			WebDriverWait(driver, 100).until( lambda driver: driver.find_element_by_xpath('/html/body/div[7]/div/div/ul/li[2]/a/img'))
			time.sleep(2)
			
			actions = ActionChains(driver)
			#driver.find_element_by_xpath('/html/body/div[8]/div[2]/div[2]/div[2]/ul[1]/li[1]/div/div[1]').click()
			blank = driver.find_element_by_xpath('/html/body/div[8]/div[2]/div[2]/div[2]/ul[1]/li[1]/ol/li[1]')
			empty_space = actions.move_to_element(blank)
			empty_space.click().perform()
			driver.find_element_by_xpath("/html/body/div[8]/ul/li[12]/a").click()
			driver.find_element_by_link_text("予定を登録する").click()
			driver.find_element_by_xpath("//*[@id='jsiSchDateDummy']").click()
			driver.find_element_by_css_selector("a[href*='#20190315']").click()
			driver.find_element_by_link_text("登録する").click()
			time.sleep(2)

			obj = driver.switch_to.alert
			obj.accept()

	# driver.quit()
exit()
