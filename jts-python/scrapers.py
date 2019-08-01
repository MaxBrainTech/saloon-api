# my_variable = "Welcome to python"
# print(my_variable)

import selenium
import datetime
import time 
import json
import requests
import slack
from selenium.common.exceptions import NoSuchElementException        
from selenium import webdriver
from selenium.webdriver.firefox.options import Options
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
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


# japanese date format

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
	
ncdte = datetime.datetime.now() + datetime.timedelta(days=28)
ndate = ncdte.strftime("%Y")+ncdte.strftime("%m")+ncdte.strftime("%d")


#check xpath then set xpath value

def set_xpath_value(xpath):
    try:
        driver.find_element_by_xpath(xpath)
        WebDriverWait(driver, 100).until( lambda driver: driver.find_element_by_xpath(xpath))
        xpath_value = driver.find_element_by_xpath(xpath).text
    except NoSuchElementException:
        return ''
    return xpath_value
#check xpath then set xpath value

def set_class_name(class_name):
    try:
        driver.find_element_by_css_selector(class_name)
        WebDriverWait(driver, 100).until( lambda driver: driver.find_element_by_css_selector(class_name))
        class_value = driver.find_element_by_css_selector(class_name).text
        print(class_value)
        exit()
    except NoSuchElementException:
        return ''
    return class_value

def set_xpath_value_attribute(xpath):
    try:
        driver.find_element_by_xpath(xpath)
        WebDriverWait(driver, 100).until( lambda driver: driver.find_element_by_xpath(xpath))
        xpath_value = driver.find_element_by_xpath(xpath).get_attribute("innerHTML")
    except NoSuchElementException:
        return ''
    return xpath_value


def set_xpath_click(xpath):
    try:
        driver.find_element_by_xpath(xpath)
        WebDriverWait(driver, 100).until( lambda driver: driver.find_element_by_xpath(xpath))
        xpath_click = driver.find_element_by_xpath(xpath)
    except NoSuchElementException:
        return ''
    return xpath_click

def get_reservation_id(num_reservation, reservation_ids):
	for x in range(1, int(num_reservation)):
		reservation_number = set_xpath_value('/html/body/div[3]/div/form[3]/table/tbody/tr['+str(x)+']/td[3]/p[2]/a')	
		reservation_number = reservation_number.replace("(", "")
		reservation_number = reservation_number.replace(")", "")
		reservation_ids.append(reservation_number)
	return reservation_ids

# get data form reservation ext detail page

def reservation_ext(driver):
	reservation_data = []
	
	reservation_number = set_xpath_value('//*[@id="reserveId"]')
	status = set_xpath_value('/html/body/div[3]/div/form/table[1]/tbody/tr/td[2]/p')
	visit_date = set_xpath_value('//*[@id="rsvDate"]')
	reservation_route = set_xpath_value('/html/body/div[3]/div/form/table[2]/tbody/tr[2]/td')
	menu = set_xpath_value('/html/body/div[3]/div/form/table[2]/tbody/tr[3]/td')
	coupon_name = set_xpath_value('/html/body/div[3]/div/form/table[2]/tbody/tr[4]/td')
	staff = set_xpath_value('/html/body/div[3]/div/form/table[3]/tbody/tr[1]/td/table/tbody/tr/td[1]/div')
	staff_duration = set_xpath_value('/html/body/div[3]/div/form/table[3]/tbody/tr[1]/td/table/tbody/tr/td[2]/div')
	service_name = set_xpath_value('/html/body/div[3]/div/form/table[3]/tbody/tr[2]/td/table/tbody/tr/td[1]/div')
	service_duration = set_xpath_value('/html/body/div[3]/div/form/table[3]/tbody/tr[2]/td/table/tbody/tr/td[2]/div')
	kana_name = set_xpath_value('/html/body/div[3]/div/form/table[6]/tbody/tr[2]/td')
	kanji_name = set_xpath_value_attribute('/html/body/div[3]/div/form/table[6]/tbody/tr[2]/td')
	if(kanji_name == ''):
		kanji_name = set_xpath_value_attribute('/html/body/div[3]/div/form/table[4]/tbody/tr[2]/td')
	kanji_name_arr = kanji_name.split('<img')
	kanji_name = kanji_name_arr[0]
	kanji_name = kanji_name.replace("- ", "")
	kanji_name = kanji_name.replace("-&nbsp;", " ")
	kanji_name = kanji_name.replace("&nbsp;", " ")
	customer_phone_number = set_xpath_value('/html/body/div[3]/div/form/table[7]/tbody/tr[3]/td')
	number_of_visits = set_xpath_value('/html/body/div[3]/div/form/table[7]/tbody/tr[5]/td')
	reservation_amount = set_xpath_value('/html/body/div[3]/div/form/div[6]/table/tbody/tr/td/div/div/div[1]/p[2]')
	if(reservation_amount == ''):
		reservation_amount = set_xpath_value('/html/body/div[3]/div/form/table[4]/tbody/tr[7]/td/dl[3]/dd')
	if(reservation_amount == ''):
		reservation_amount = '0 円'
	reservation_data = {
		'reservation_number': reservation_number,
		'status': status,
		'visit_date': visit_date,
		'reservation_route': reservation_route,
		'menu': menu,
		'coupon_name': coupon_name,
		'staff': staff,
		'staff_duration': staff_duration,
		'service_name': service_name,
		'service_duration': service_duration,
		'kana_name': kana_name,
		'kanji_name': kanji_name,
		'customer_phone_number': customer_phone_number,
		'reservation_amount': reservation_amount,
		'number_of_visits': number_of_visits
	}
	return reservation_data
	
 # get data form reservation net detail page

def reservation_net(driver):
	status = staff = service_name = service_duration = kana_name = kanji_name = customer_phone_number = ''
	reservation_amount = '0 円'
	used_points = '0 ポイント'
	reservation_number = set_xpath_value('//*[@id="reserveId"]')
	visit_date = set_xpath_value('//*[@id="rsvDate"]')
	
	for i in range(1, 20): 
		status_label = set_xpath_value('/html/body/div[3]/div/form/table['+str(i)+']/tbody/tr/th[2]')
		if(status_label == 'ステータス'):
			status = set_xpath_value('/html/body/div[3]/div/form/table['+str(i)+']/tbody/tr/td[2]/p')
		
		staff_label = set_xpath_value('/html/body/div[3]/div/form/table['+str(i)+']/tbody/tr[1]/th')
		if(staff_label == 'スタッフ'):
			staff = set_xpath_value('/html/body/div[3]/div/form/table['+str(i)+']/tbody/tr[1]/td/table/tbody/tr/td[1]/div')
			

		service_name_label = set_xpath_value('/html/body/div[3]/div/form/table['+str(i)+']/tbody/tr[2]/th')
		if(service_name_label == '設備'):
			service_name = set_xpath_value('/html/body/div[3]/div/form/table['+str(i)+']/tbody/tr[2]/td/table/tbody/tr/td[1]/div')
			service_duration = set_xpath_value('/html/body/div[3]/div/form/table['+str(i)+']/tbody/tr[2]/td/table/tbody/tr/td[2]')

		kana_name_label = set_xpath_value('/html/body/div[3]/div/form/table['+str(i)+']/tbody/tr[1]/th')
		if(kana_name_label == '氏名 (カナ)'):
			kana_name = set_xpath_value('/html/body/div[3]/div/form/table['+str(i)+']/tbody/tr[1]/td/div[1]')

		
		kanji_name_label = set_xpath_value('/html/body/div[3]/div/form/table['+str(i)+']/tbody/tr[2]/th')
		if(kanji_name_label == '氏名 (漢字)'):
			kanji_name = set_xpath_value('/html/body/div[3]/div/form/table['+str(i)+']/tbody/tr[2]/td')
			kanji_name_arr = kanji_name.split('<img')
			kanji_name = kanji_name_arr[0]
			kanji_name = kanji_name.strip()
			kanji_name = kanji_name.replace("-", "")
			kanji_name = kanji_name.replace("&nbsp;", " ")

		customer_phone_number_label = set_xpath_value('/html/body/div[3]/div/form/table['+str(i)+']/tbody/tr[3]/th')
		if(customer_phone_number_label == '電話番号'): 
			customer_phone_number = set_xpath_value('/html/body/div[3]/div/form/table['+str(i)+']/tbody/tr[3]/td')
	
		
		reservation_amount_label = set_xpath_value('/html/body/div[3]/div/form/div['+str(i)+']/table/tbody/tr/td/div/div/div[1]/p[1]')
		if(reservation_amount_label == '現計'):
			reservation_amount = set_xpath_value('/html/body/div[3]/div/form/div['+str(i)+']/table/tbody/tr/td/div/div/div[1]/p[2]')

		inner_reservation_amount_label = set_xpath_value('/html/body/div[3]/div/form/div['+str(i)+']/div/table/tbody/tr[3]/th[1]')
		if inner_reservation_amount_label == '合計金額' and reservation_amount == '0 円':
			reservation_amount = set_xpath_value('/html/body/div[3]/div/form/div['+str(i)+']/div/table/tbody/tr[3]/td[1]')

		inner_reservation_amount_label4 = set_xpath_value('/html/body/div[3]/div/form/div['+str(i)+']/div/table/tbody/tr[4]/th[1]')
		if inner_reservation_amount_label4 == '合計金額' and reservation_amount == '0 円':
			reservation_amount = set_xpath_value('/html/body/div[3]/div/form/div['+str(i)+']/div/table/tbody/tr[4]/td[1]')

		inner_reservation_amount_label_div11 = set_xpath_value('/html/body/div[3]/div/form/div[11]/div/table/tbody/tr[3]/th[1]')
		if inner_reservation_amount_label_div11 == '合計金額' and reservation_amount == '0 円':
			reservation_amount = set_xpath_value('/html/body/div[3]/div/form/div[11]/div/table/tbody/tr[3]/td[1]')
		
		used_point_label = set_xpath_value('/html/body/div[3]/div/form/div['+str(i)+']/div/table/tbody/tr[2]/th')

		if(used_point_label == '利用ポイント'):
			used_points = set_xpath_value('/html/body/div[3]/div/form/div['+str(i)+']/div/table/tbody/tr[2]/td[1]/b')

		used_point_sec_label = set_xpath_value('/html/body/div[3]/div/form/div['+str(i)+']/div/table/tbody/tr[3]/th')
		if(used_point_sec_label == '利用ポイント'):
			used_points = set_xpath_value('/html/body/div[3]/div/form/div['+str(i)+']/div/table/tbody/tr[3]/td[1]/b')
	
	
	reservation_amount = reservation_amount.replace("～", "")	
	
	reservation_data = {
		'reservation_number': reservation_number,
		'status': status,
		'visit_date': visit_date,
		'staff': staff,
		'service_name': service_name,
		'service_duration': service_duration,
		'kana_name': kana_name,
		'kanji_name': kanji_name,
		'customer_phone_number': customer_phone_number,
		'reservation_amount': reservation_amount,
		'used_points': used_points,
	}
	return reservation_data

	
		
try:
	userRecords = getSalonboarUser()
	for x in userRecords:

		url = "https://salonboard.com/login/"
		reservation_detail_ext_url = "https://salonboard.com/KLP/reserve/ext/extReserveDetail/?reserveId="
		reservation_detail_net_url = "https://salonboard.com/KLP/reserve/net/reserveDetail/?reserveId="
		user_id = x[0]
		username = x[1]
		password = x[2]
		# user_id = '33'
		# username = 'CC21324'
		# password = 'zedinter01!'
		options = Options()
		options.add_argument('-headless')
		driver = webdriver.Firefox(firefox_options=options, executable_path='/usr/local/bin/geckodriver')

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
			driver.find_element_by_xpath('/html/body/div[7]/div/div/ul/li[2]/a/img').click()
			WebDriverWait(driver, 100).until( lambda driver: driver.find_element_by_xpath('//*[@id="dispDateTo"]'))
			driver.find_element_by_xpath('//*[@id="dispDateTo"]').click()
			ndate = str(ndate)
			WebDriverWait(driver, 100).until( lambda driver: driver.find_element_by_xpath('//a[@href="#'+ndate+'"]'))
			driver_page = driver.find_element_by_xpath('//a[@href="#'+ndate+'"]').click();
			time.sleep(2)
			WebDriverWait(driver, 100).until( lambda driver: driver.find_element_by_xpath('//*[@id="search"]'))
			driver.find_element_by_xpath('//*[@id="search"]').click()
			WebDriverWait(driver, 100).until(lambda driver: driver.find_element_by_xpath('/html/body/div[3]/div/div[2]/div[1]/p/b'))
			reservation_count = driver.find_element_by_xpath('/html/body/div[3]/div/div[2]/div[1]/p/b')
			count = int(reservation_count.text)
			# count = int(100)
			print(count)
			driver_page = driver
			funReserveIds = []
			reserveIds = []
			num_reservation = 50
			pass_num_reservation = 51
			if(count > num_reservation):
				remaining = count % int(num_reservation)
				if(remaining > 0):
					remaining = remaining + 1
					
				total = int(count / int(num_reservation))
				# if(remaining !=0):
				# 	total = total+1
				for y in range(1, total+1):
					funReserveIds = get_reservation_id(pass_num_reservation, reserveIds)
					try :
						driver_page = set_xpath_click('/html/body/div[3]/div/form[3]/div[2]/div[2]/div/p[3]/a')
						driver_page.click()
					except:
						emptyPage = ''
				if(remaining !=0):
					funReserveIds2 = get_reservation_id(remaining, reserveIds)
					
			else:
				reserveIds = get_reservation_id(count, reserveIds)
			# print(reserveIds)	
			reservation_data_all = dict()
			for n in range(len(reserveIds)):
				reservationId = reserveIds[n]
				if(reservationId.find('B') >=0):
					current_url = reservation_detail_net_url+reservationId
					driver.get(current_url)
					time.sleep(5)
					reservation_data_all[str(n)] =  reservation_net(driver)

			reservation_data_all['userId'] =  user_id
			payload = json.dumps(reservation_data_all, sort_keys=True, indent=4)
			# print(payload)
			# exit()
			
			req = requests.post("https://jtsboard.com/cron_schedulers/salon_board_scraper", data=payload )

			# reservation_data_all['userId'] =  '33'
			# payload_new = json.dumps(reservation_data_all, sort_keys=True, indent=4)
			# req = requests.post("https://jtsboard.com/cron_schedulers/salon_board_scraper_testing", data=payload_new )
			# req = requests.post("https://dev.jtsboard.com/cron_schedulers/salon_board_scraper", data=payload )
			res = req.text
			status = req.status_code

			print(res)
			print(status)
			if(status != 200):
				slack.slack_notify(status)

			driver.quit()
except:
	slack.slack_notify('SyntaxError.')
exit()
