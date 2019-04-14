# RemoteControl-RPI


This dockerfile build IR remote control image for RaspberryPi(ARM) with Docker. The remote container is built on Alpine base image using [Pigpio](http://abyz.me.uk/rpi/pigpio/examples.html#Python_irrp_py) that is Python3.5 Library. The app is then available from any other systems by WebAPI. 

## Requirement
The app must be run on RaspberryPi and be required environment that is runnable Compose.

## Usage

### Run 
       $ docker-compose up -d

### IR Console Page. 
       http://[IP address]:20080
       
![console_image](https://user-images.githubusercontent.com/14244767/56091180-42ef9f80-5ee6-11e9-926c-3056de00c5ab.png)


#### Send IR signal  
* `IR FileName` must be set IR filename. It read automatically filenames under ***remote/source*** directory and set items. If you select it, the page set automatically selectable items to combobox named 'IR Command'. 
* `IR Command` must be set IR command in you assigned 'IR filename' in the above Combobox.


#### Scheme 
The following is a scheme example. 
* In : GPIO 24
* Out : GPIO 25

<img src="https://user-images.githubusercontent.com/14244767/56091154-0328b800-5ee6-11e9-98b9-50f3f91088b6.png" width="500px">
       

### Generate IR file

1. Enter remote docker container. 

       $ docker-compose exec remote sh

2. Type following command

       $ addir [IRFilename] [IRCommandname]
       
3. Push a button on remote device 
       
#### example
       $ addir tv power_on
       -- button pushed --
       Recording Press key for 'power_on' Okay
       
### Parameters 

#### GPIO port Setting
* `IR_IN_GPIO_PORT` IR file input GPIO port
* `IR_OUT_GPIO_PORT` IR file output GPIO port

'env' file sets the ports
```env
       IR_IN_GPIO_PORT=24
       IR_OUT_GPIO_PORT=25
```
 
### WebAPI
Any Other systems are able to control the app by WebAPI. You send IR command by way of them, it executes command and returns the result json object then.

       http://[IP address]:20080/api/remotecontrol.php?filename=[IRFilename]&command=[IRCommandname]


#### example
URL included WebAPI
```api 
       http://[IP address]:20080/api/remotecontrol.php?filename=samplefile&command=samplecommand1
```     

Result json object
```json       
       {"commandstatus":"success","filename":"samplefile","sendcommand":"samplecommand1"}
```