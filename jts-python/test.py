import time
from selenium import webdriver
from selenium.webdriver.common.action_chains import ActionChains
from selenium.webdriver.common.desired_capabilities import DesiredCapabilities
from selenium.webdriver.support.ui import WebDriverWait

# driver = webdriver.Remote(
#     command_executor='http://localhost:4444/wd/hub',
#     desired_capabilities=DesiredCapabilities.FIREFOX)
driver = webdriver.Remote(command_executor='http://localhost:4444/wd/hub', desired_capabilities=webdriver.DesiredCapabilities.FIREFOX)
# Googleにアクセス
driver.get('https://www.google.co.jp/')
driver.save_screenshot('test.png')
# 終了
driver.quit()

# time.sleep(5)
# driver.quit()

