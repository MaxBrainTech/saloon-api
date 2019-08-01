# -*- coding: utf-8 -*-
import sys
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
from selenium.webdriver.support.ui import Select
import re
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.desired_capabilities import DesiredCapabilities
from selenium.webdriver.firefox.firefox_binary import FirefoxBinary
from selenium.webdriver.common.alert import Alert
from pyvirtualdisplay import Display

reservation_id = sys.argv[1]
# reservation_id = 4504
staff_name = ''
reservation_date = ''
reservation_time = ''
start_hours = ''
start_minutes = ''
end_hours = ''
end_minutes = ''
all_day = ''
employee_name = ''
x = ''
# print(id)
# print("hello")
# exit()
def japanese_date(x):
	cday = x.strftime("%a")
	if cday == "Mon":
		cnday = "月" 
	elif cday == "Tue":
		cnday = "水" 
	elif cday == "Wed":
		cnday = "水" 
	elif cday == "Thu":
		cnday = "木" 
	elif cday == "Fri":
		cnday = "金" 
	elif cday == "Sat":
		cnday = "土" 
	elif cday == "Sun":
		cnday = "日" 
	return  x.strftime("%Y")+"年"+x.strftime("%m")+"月"+x.strftime("%d")+"日（ "+cnday+"）"
	
# ncdte = datetime.datetime.now() + datetime.timedelta(days=1)
# ndate = ncdte.strftime("%Y")+ncdte.strftime("%m")+ncdte.strftime("%d")

def getSalonboarUser():

    try:
    	
        global staff_name
        global reservation_date
        global reservation_time
        global start_hours
        global start_minutes
        global end_hours
        global end_minutes
        global all_day
        global employee_name
        global x
        mySQLConnection = mysql.connector.connect(host='34.85.64.241',
                                             database='jtsboard_jts',
                                             user='jts',
                                             password='Jts5678?')
        cursor = mySQLConnection.cursor()
        sql_select_query = """select * from reservations where id = %s"""
        cursor.execute(sql_select_query , (reservation_id,))
        record = cursor.fetchall()
        x = str(record[0][8])
        x = re.sub('-','',x)
        x = '#' + x
        # print(x)
        # exit()
        #reservation_date = japanese_date(record[0][8])
        reservation_date = "2019年03月22日（ 水）"
        all_day = str(record[0][7])
        start_time = str(record[0][12])
        end_time = str(record[0][13])
        start_hours, start_minutes, sec = map(int, start_time.split(':'))
        end_hours, end_minutes, sec = map(int, end_time.split(':'))
        sql_select_query = """select name from employees where id = %s"""
        employee_id = int(record[0][4])
        cursor.execute(sql_select_query , (employee_id,))
        record = cursor.fetchall()
        employee_name = record[0][0]
        # print(reservation_date)
        # print(start_time)
        # print(start_hours)
        # print(start_minutes)
        # print(end_hours)
        # print(end_minutes)
        # print(all_day)
        # print(employee_name)
        # exit()
    except mysql.connector.Error as error:
        print("Failed to get record from database: {}".format(error))
    finally:
        if (mySQLConnection.is_connected()):
            cursor.close()
            mySQLConnection.close()

userRecords = getSalonboarUser()

print(employee_name.decode("utf-8"))
print(reservation_date)
print(start_hours)
print(start_minutes)
print(end_hours)
print(end_minutes)
print(all_day)

url = "https://salonboard.com/login/"
reservation_detail_ext_url = "https://salonboard.com/KLP/reserve/ext/extReserveDetail/?reserveId="
reservation_detail_net_url = "https://salonboard.com/KLP/reserve/net/reserveDetail/?reserveId="
username = "CC21324"
password = "zedinter01!"
options = Options()
options.add_argument('-headless')

driver = webdriver.Firefox(executable_path=r'/usr/local/bin/geckodriver', options=options)
print("before driver")



if __name__ == "__main__":
	#driver = webdriver.Firefox()
	#driver = webdriver.Remote(command_executor='http://localhost:4444/wd/hub', desired_capabilities=webdriver.DesiredCapabilities.FIREFOX)
	driver.get(url)
	print("before login")
	uname = driver.find_element_by_name("userId")# ← find by element name
	uname.send_keys(username) # ← enters the username in textbox
	passw = driver.find_element_by_name("password")
	passw.send_keys(password)  #← enters the password in textbox
	# Find the submit button using class name and click on it.
	submit_button = driver.find_element_by_class_name("input_area_btn_01").click()
	print("after login")

	WebDriverWait(driver, 100).until( lambda driver: driver.find_element_by_xpath('//*[@id="globalNavi"]/ul[2]/li[1]/a/img'))
	time.sleep(2)
	driver.find_element_by_xpath('//*[@id="globalNavi"]/ul[2]/li[1]/a/img').click()
	WebDriverWait(driver, 100).until( lambda driver: driver.find_element_by_xpath('/html/body/div[7]/div/div/ul/li[2]/a/img'))


	## for salon off day
	# WebDriverWait(driver, 100).until( lambda driver: driver.find_element_by_class_name("mod_btn_calendar_01"))
	# actions = ActionChains(driver)
	# cal = driver.find_element_by_class_name("mod_btn_calendar_01")
	# calendar = actions.move_to_element(cal)
	# calendar.click().perform()

	# WebDriverWait(driver, 100).until( lambda driver: driver.find_element_by_link_text("次の月"))
	# next_month = driver.find_element_by_link_text("次の月")
	# next_month_go = actions.move_to_element(next_month)
	# next_month_go.click().perform()


	# WebDriverWait(driver, 100).until( lambda driver: driver.find_element_by_css_selector("a[href*='/KLP/schedule/salonSchedule/?date=20190422']"))
	# driver.find_element_by_css_selector("a[href*='/KLP/schedule/salonSchedule/?date=20190422']").click()


	# time.sleep(2)
	# driver.find_element_by_css_selector("a[href*='#201905']").click()
	# time.sleep(2)
	# driver.find_element_by_link_text("16").click


	time.sleep(2)
	actions = ActionChains(driver)
	WebDriverWait(driver, 100).until( lambda driver: driver.find_element_by_xpath('/html/body/div[8]/div[2]/div[2]/div[2]/ul[1]/li[1]/ol/li[2]'))
	blank = driver.find_element_by_xpath('/html/body/div[8]/div[2]/div[2]/div[2]/ul[1]/li[1]/ol/li[2]')
	empty_space = actions.move_to_element(blank)
	empty_space.click().perform()

	
	driver.find_element_by_xpath("/html/body/div[8]/ul/li[12]/a").click()
	driver.find_element_by_link_text("予定を登録する").click()
	time.sleep(2)
	select = Select(driver.find_element_by_name("staffId"))
	select.select_by_visible_text(employee_name.decode("utf-8"))

	driver.find_element_by_xpath('//*[@id="jsiSchDateDummy"]').click()
	time.sleep(2)

	driver.find_element_by_css_selector("a[href*='#20190422']").click()
	#driver.find_element_by_css_selector("a[href*='"+x+"']").click()

	# driver.find_element_by_link_text(str(x)).click()
	time.sleep(2)

	select = Select(driver.find_element_by_name("rsvHour"))
	select.select_by_visible_text(str(start_hours))

	print("start_hours")

	select = Select(driver.find_element_by_name("rsvMinute"))
	if(start_minutes == 0):
		select.select_by_visible_text('00')
	else:
		select.select_by_visible_text(str(start_minutes))
	print("start_minutes")

	select = Select(driver.find_element_by_name("schEndHour"))
	select.select_by_visible_text(str(end_hours))
	print(end_hours)

	select = Select(driver.find_element_by_name("schEndMinute"))
	if(end_minutes == 0):
		select.select_by_visible_text('00')
	else:
		select.select_by_visible_text(str(end_minutes))
	print(end_minutes)

	if(all_day == '1'):
		driver.find_element_by_xpath('//*[@id="allDayFlg"]').click()
	driver.find_element_by_xpath('//*[@id="regist"]').click()

	time.sleep(2)
	try:
		alert = driver.switch_to.alert
		print(alert.text)
		time.sleep(3)
		alert.accept()
	except:
		# print "no alert to accept"
		print("No alert")
	driver.quit()
exit()
