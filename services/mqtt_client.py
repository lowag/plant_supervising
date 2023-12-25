# python3.6

#import random
import json
import mysql.connector
import datetime
mydb = mysql.connector.connect(
  host="IP_ADDRESS",
  user="USERNAME",
  password="PASSWORD",
  database="DATABASE_NAME",
  use_pure=True)
mycursor=mydb.cursor()

from paho.mqtt import client as mqtt_client
start=True
company={
    "plant1":{
    "machine1":{
            "id":1,
            "act_speed":0,
            "shift_max_speed":0,
            "actual_length":0,
            "shift_length":0,
            "max_shift_length":0,
            "uptime":0,
            "downtime":0,
            "sum_shift_shutdown":0,
            "utilization":0
        },
    "machine2":{
            "id":2,
            "act_speed":0,
            "actual_length":0,
            "shift_max_speed":0,
            "shift_length":0,
            "max_shift_length":0,
            "uptime":0,
            "downtime":0,
            "sum_shift_shutdown":0,
            "utilization":0
        },
    "machine4":{
            "id":3,
            "act_speed":0,
            "actual_length":0,
            "shift_max_speed":0,
            "shift_length":0,
            "max_shift_length":0,
            "uptime":0,
            "downtime":0,
            "sum_shift_shutdown":0,
            "utilization":0
        }
    },
    "plant2":{
        "machine1":{
            "id":7,
            "act_speed":0,
            "actual_length":0,
            "shift_max_speed":0,
            "shift_length":0,
            "max_shift_length":0,
            "uptime":0,
            "downtime":0,
            "sum_shift_shutdown":0,
            "utilization":0
        },
        "machine2":{
            "id":8,
            "act_speed":0,
            "actual_length":0,
            "shift_max_speed":0,
            "shift_length":0,
            "max_shift_length":0,
            "uptime":0,
            "downtime":0,
            "sum_shift_shutdown":0,
            "utilization":0
        }

        },
    "plant3":{
        "machine1":{
            "id":9,
            "act_speed":0,
            "actual_length":0,
            "shift_max_speed":0,
            "shift_length":0,
            "max_shift_length":0,
            "uptime":0,
            "downtime":0,
            "sum_shift_shutdown":0,
            "utilization":0
        },
        "machine2":{
            "id":10,
            "act_speed":0,
            "actual_length":0,
            "shift_max_speed":0,
            "shift_length":0,
            "max_shift_length":0,
            "uptime":0,
            "downtime":0,
            "sum_shift_shutdown":0,
            "utilization":0
        }

        },
    "plant4":{
        "machine1":{
            "id":11,
            "act_speed":0,
            "actual_length":0,
            "shift_max_speed":0,
            "shift_length":0,
            "max_shift_length":0,
            "uptime":0,
            "downtime":0,
            "sum_shift_shutdown":0,
            "utilization":0
        },
        "machine2":{
            "id":12,
            "act_speed":0,
            "actual_length":0,
            "shift_max_speed":0,
            "shift_length":0,
            "max_shift_length":0,
            "uptime":0,
            "downtime":0,
            "sum_shift_shutdown":0,
            "utilization":0
        }

        }
}

broker = 'IP_ADDRESS'
port = 1883
topic = "company/#"
client_id =  "CLIENT_ID"
username = 'MQTT_USERNAME'
password = 'MQTT_PASSWORD'


def connect_mqtt() -> mqtt_client:
    def on_connect(client, userdata, flags, rc):
        if rc == 0:
            print("Connected to MQTT Broker!")
        else:
            print("Failed to connect, return code %d\n", rc)

    client = mqtt_client.Client("server")
    client.username_pw_set(username, password)
    client.on_connect = on_connect
    client.connect(broker, port)
    return client


def subscribe(client: mqtt_client):
    def on_message(client, userdata, msg):
        global mydb,company,start,mycursor
        print(f"Received `{msg.payload.decode()}` from `{msg.topic}` topic")
        top=msg.topic.split("/")
        if not mydb.is_connected():
            mydb = mysql.connector.connect(host="IP_ADDRESS",user="MYSQL_USERNAME",password="MYSQL_PASSWORD",database="DATABASE_NAME",use_pure=True)
            time.sleep(0.5)
            mycursor=mydb.cursor()
        if 1:
            m = json.loads(msg.payload.decode())
           
            if "machine" in m.keys():
                if "speed" in m.keys():
                    
                    if (int(m["speed"])>15 and int(company[top[1]][m["machine"]]["act_speed"])<15) or (int(m["speed"])>15 and start==True):
                        start=False
                        company[top[1]][m["machine"]]["uptime"]=datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
                        print("benn2")
                        sql="insert into operatings (asset_id,uptime) values ('"+ str(company[top[1]][m["machine"]]["id"])+"','"+ company[top[1]][m["machine"]]["uptime"]+"' )"
                        mycursor.execute(sql)
                        mydb.commit()
                        client.publish(msg.topic,'{"machine":"'+m["machine"]+'","uptime":"'+company[top[1]][m["machine"]]["uptime"]+'"}')
                    
                    elif (int(m["speed"])==0 and int(company[top[1]][m["machine"]]["act_speed"])>0) or start==True:
                        company[top[1]][m["machine"]]["downtime"]=datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
                        start=False
                        if not mydb.is_connected():
                            mydb = mysql.connector.connect(
                            host="MYSQL_SERVER_IP",
                            user="MYSQL_USERNAME",
                            password="MYSQL_PASSWORD",
                            database="DATABESE_NAME",
                            use_pure=True
                             )
                            time.sleep(0.5)
                            mycursor=mydb.cursor()
                        sql = "SELECT max(operating_id) as id FROM operatings WHERE asset_id ='"+str(company[top[1]][m["machine"]]["id"])+"'"
                        mycursor.execute(sql)
                        id=mycursor.fetchone()[0]
                        sql="update operatings SET downtime='"+company[top[1]][m["machine"]]["downtime"]+"' WHERE operating_id='"+str(id)+"'"
                        mycursor.execute(sql)
                        mydb.commit()
                        client.publish(msg.topic,'{"machine":"'+m["machine"]+'","downtime":"'+company[top[1]][m["machine"]]["downtime"]+'"}')
                        
                    if abs(int(m["speed"])-int(company[top[1]][m["machine"]]["act_speed"]))>5 or int(m["speed"])==0 and int(company[top[1]][m["machine"]]["act_speed"])>0:
                        sql="insert into machine_speeds (asset_id,speed) values ('"+ str(company[top[1]][m["machine"]]["id"])+"','"+m["speed"]+"' )"
                        mycursor.execute(sql)
                        mydb.commit()
                    company[top[1]][m["machine"]]["act_speed"]=m["speed"]
                    if int(m["speed"])>int(company[top[1]][m["machine"]]["shift_max_speed"]):
                        company[top[1]][m["machine"]]["shift_max_speed"]=m["speed"]
                        client.publish(msg.topic,'{"machine":"'+m["machine"]+'","shift_max_speed":"'+str(company[top[1]][m["machine"]]["shift_max_speed"])+'"}')
 
            if "actual_length" in m.keys():
                 if int(m["actual_length"])<30 and int(company[top[1]][m["machine"]]["actual_length"])>30:
                     company[top[1]][m["machine"]]["shift_length"]+=int(company[top[1]][m["machine"]]["actual_length"])
                 client.publish(msg.topic,'{"machine":"'+m["machine"]+'","shift_length":"'+str(company[top[1]][m["machine"]]["shift_length"])+'"}')

                 company[top[1]][m["machine"]]["actual_length"]=m["actual_length"]
           
            if "topic" in m.keys():
                    print(m['topic'])
                    if m["topic"]=="company/+/machines/+":
                        locs=["plant1","plant2","plant3","plant4"]
                    else:
                        l=m["topic"].split("/");
                        locs=[l[1]]
                    
                    for loc in locs:
                        for machines in company[loc]:
                        
                            mes='{"machine":"'+machines+'","act_speed":"'+str(company[loc][machines]["act_speed"])+'","shift_max_speed":"'+str(company[loc][machines]["shift_max_speed"])+'","shift_length":"'+str(company[loc][machines]["shift_length"])+'","max_shift_length":"'+str(company[loc][machines]["max_shift_length"])+'","uptime":"'+str(company[loc][machines]["uptime"])+'","downtime":"'+str(company[loc][machines]["downtime"])+'","sum_shift_shutdown":"'+str(company[loc][machines]["sum_shift_shutdown"])+'","utilization":"'+str(company[loc][machines]["utilization"])+'"}'
                        
                            client.publish("company/"+loc+"/machines/"+machines,mes)
                         
        else:
            print("exception happened")
            
        #
    client.subscribe(topic)
    client.subscribe("server")
    client.on_message = on_message


def run():
    try:
        client = connect_mqtt()
        subscribe(client)
        client.loop_forever()
    except:
        print("exception")

if __name__ == '__main__':
    run()

