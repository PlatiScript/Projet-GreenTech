#define motor2_clock 7
#define motor2_hf 6
#define motor2_sens 5

#define motor1_clock 4
#define motor1_hf 3
#define motor1_sens 2

#define motor1_cf 8
#define motor2_cf 9

#define bras1_up 38
#define bras1_down 40

#define bras2_down 36
#define bras2_up 34

#define shield_enable 10 // PIN 10 NORMALEMENT

#define pompe_eau 46
#define pompe_air 47
#define electro_vanne 48
#define electro_vanne_cmd 49

#define speedMotor 5

#include <Servo.h>
Servo servohaut;


int pos[9][9] = {{0, 365, 730, 0, 365, 730, 0, 365, 730}, {0, 0, 0, 300, 300, 300, 600, 600, 600}};
int xpos, ypos;
int posS = 0;

int case_serre;
String message;

bool is_calib;
bool is_moving;

void runMotor(int motor, bool sens, int _speed) {
  if (motor == 1) {
    digitalWrite(motor1_sens, sens);
    digitalWrite(motor1_hf, 1);
    digitalWrite(motor1_clock, 1);
    delay(speedMotor);
    digitalWrite(motor1_clock, 0);
    if (!sens) {
      ypos--;
    } else {
      ypos++;
    }
  } else {
    digitalWrite(motor2_sens, sens);
    digitalWrite(motor2_hf, 1);
    digitalWrite(motor2_clock, 1);
    delay(speedMotor);
    digitalWrite(motor2_clock, 0);
    if (!sens) {
      xpos--;
    } else {
      xpos++;
    }
  }
}
void deplacement(int x, int y, bool descente = false) {
  digitalWrite(22, HIGH);
  is_moving = true;
  if (descente) {
    digitalWrite(bras2_down, HIGH);
    digitalWrite(bras1_down, HIGH);
  }
  if (xpos < 400) {
    servohaut.write(180);
  }
  if (xpos < x && digitalRead(motor2_cf) == 1) {

    for (int i = xpos; i < x; i++) {
      runMotor(2, HIGH, 5);
    }
  } else {
    for (int i = xpos; i > x; i--) {
      runMotor(2, LOW, 5);
    }
  }
  if (ypos < y && digitalRead(motor1_cf) == 1) {
    for (int i = ypos; i < y; i++) {
      runMotor(1, HIGH, 5);
    }
    Serial.println(ypos);


  } else {
    for (int i = ypos; i > y; i--) {
      runMotor(1, LOW, 5);
    }
    Serial.println(ypos);

  }
  digitalWrite(22, LOW);
  is_moving = false;
}


void arrosage(int case_serre) {
  digitalWrite(shield_enable, HIGH);
  Serial.print("arrosage ");
  Serial.println(case_serre);
  
  deplacement(pos[0][case_serre - 1], pos[1][case_serre - 1]);

  /*digitalWrite(bras2_down, HIGH);
    digitalWrite(bras1_down, HIGH);
    delay(23000);
    digitalWrite(bras2_down, LOW);
    digitalWrite(bras1_down, LOW);
    digitalWrite(bras2_up, HIGH);
    digitalWrite(bras1_up, HIGH);
    delay(21000);*/
      digitalWrite(shield_enable, LOW);

}

void calibration() {
  digitalWrite(shield_enable, HIGH);
  servohaut.write(180);
  is_moving = true;
  Serial.println("Calibration en cours");
  while (true) {
    if (digitalRead(motor1_cf) == 1) {
      Serial.println("Calibration de l'axe X termine");
      xpos = 0;
      break;
    } else {
      runMotor(1, LOW, 5);
    }
  }
  while (true) {
    if (digitalRead(motor2_cf) == 1) {
      Serial.println("Calibration de l'axe Y termine");
      ypos = 0;
      break;
    } else {
      runMotor(2, LOW, 5);
    }
  }
  is_calib = true;
  for (posS = servohaut.read(); posS <= 180; posS += 1) {
    servohaut.write(posS);
    delay(10);
  }
  xpos = 0;
  ypos = 0;
  Serial.println("Calibration termine");
 
  is_moving = false;
    digitalWrite(shield_enable, LOW);

}
void loop() {
  if (servohaut.read() > 100 && ypos > 350) {
    if (xpos > 410) {
      for (posS = servohaut.read(); posS >= 0; posS -= 1) {
        servohaut.write(posS);
        delay(10);
      }
    } else {
      for (posS = servohaut.read(); posS >= 90; posS -= 1) {
        servohaut.write(posS);
        delay(10);
      }
    }
  }
  if (Serial.available()) {
    message = Serial.readString();
    if (message.substring(0, message.length() - 1) == "arrosage") {
      case_serre = message.substring(message.length() - 1, message.length()).toInt();
      Serial.println(case_serre);
      arrosage(case_serre);
    }
    if (message.substring(0, message.length() - 1) == "calibration") {
      case_serre = message.substring(message.length() - 1, message.length()).toInt();
      Serial.println(case_serre);
      calibration();
    }
  }
  if (analogRead(A0) > 700) {

    runMotor(1, HIGH, 5);

    digitalWrite(22, HIGH); // Led témoin
  } if (analogRead(A0) < 250 && digitalRead(motor1_cf) == 0) {

    runMotor(1, LOW, 5);

    digitalWrite(22, HIGH); // Led témoin

  }
  if (analogRead(A1) > 700) {

    runMotor(2, HIGH, 4);

    digitalWrite(22, HIGH); // Led témoin

  } if (analogRead(A1) < 250 && digitalRead(motor2_cf) == 0) {

    runMotor(2, LOW, 4);

    digitalWrite(22, HIGH); // Led témoin

  }
  if (analogRead(1) > 450 && analogRead(1) < 550 && analogRead(0) > 450 && analogRead(0) < 550 && is_calib) {
    digitalWrite(22, LOW);
  }

}

void setup() {
  servohaut.attach(11);  // attaches the servo on pin 9 to the servo object
  is_calib = false;
  xpos = 0;
  ypos = 0;
  pinMode(motor1_sens, OUTPUT);
  pinMode(motor1_hf, OUTPUT);
  pinMode(motor1_clock, OUTPUT);

  pinMode(motor2_sens, OUTPUT);
  pinMode(motor2_hf, OUTPUT);
  pinMode(motor2_clock, OUTPUT);

  pinMode(motor1_cf, INPUT);
  pinMode(motor2_cf, INPUT);

  pinMode(bras1_down, OUTPUT);
  pinMode(bras1_up, OUTPUT);
  pinMode(bras2_down, OUTPUT);
  pinMode(bras2_up, OUTPUT);

  pinMode(pompe_air, OUTPUT);
  pinMode(pompe_eau, OUTPUT);
  pinMode(electro_vanne, OUTPUT);
  pinMode(electro_vanne_cmd, OUTPUT);

  pinMode(shield_enable, OUTPUT); // ENABLED SHIELD

  pinMode(22, OUTPUT); // 1
  pinMode(24, OUTPUT); // 2 -
  pinMode(26, OUTPUT); // 3 -
  pinMode(28, OUTPUT); // 4 - Led déplacement
  pinMode(30, OUTPUT); // 5 -

  analogWrite(15, 0); // Red
  analogWrite(14, 128); // Green
  analogWrite(13, 0);// Blue

  
  digitalWrite(pompe_air, HIGH);

  digitalWrite(bras1_up, LOW);
  digitalWrite(bras1_down, HIGH);
se
  Serial.begin(9600);
  calibration();

}

