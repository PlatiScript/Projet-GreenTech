int x;
int y;
void setup() {
  size(1280, 640);
  background(146, 219, 74);
}
void draw() {
  background(146, 219, 74);
  strokeWeight(3);  // Default
  line(642,0,642,640);
  strokeWeight(1);  // Default

  // Partie de droite
  fill(181, 255, 240);
  rect(643, 50, 637,540); // rect haut
  fill(155);
  rect(643, 0, 637,50); // rect haut
  fill(109, 87, 32);
  rect(643, 590, 637,50); // rect bas
  fill(255);
  // Partie de gauche
  translate(x*2, 0);
  rect(50, 0, 30, 640);
  translate(-x*2, y*2);
  rect(0, 100, 640, 30);
  rect(x*2+15,height/2-255,100,100);
  if (keyPressed) {
    if (keyCode == 38 && y*2 > -65) {
      y--;
    }
    if (keyCode == 40  && y*2 < 475) {
      y++;
    }
    if (keyCode == 37  && x*2 > -15) {
      x--;
    }
    if (keyCode == 39 && x*2 < 525) {
      x++;
    }
  }
}