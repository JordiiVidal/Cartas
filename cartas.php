<?php

    class Jugador{

        public $nombre;

        public $cartas;

        function __construct($nombre){

            $this->nombre = $nombre;
            $this->cartas = array();

        }

        function cogerCarta($carta){

            array_push($this->cartas, $carta);

        }


    } 

    class Carta{

        private $palo;

        private $nombre;

        private $valor;

        function __construct($palo,$nombre,$valor){
            $this->palo = $palo;
            $this->nombre = $nombre;
            $this->valor = $valor;
        }

        public function __get($property){
            if(property_exists($this, $property)) {
                return $this->$property;
            }
        }

        public function __set($property, $value){
            if(property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }

    abstract class Baraja{

        public $cartas = array();

        function barajar(){
            shuffle($this->cartas);
        }

        function repartir(){
            $carta = $this->cartas[0];
            array_splice($this->cartas, 0, 1);
            return $carta;
        }

        abstract function reiniciar();
    
    }

    class Francesa extends Baraja{
        
        function __construct(){

            $this->llenar('picas');
            $this->llenar('diamantes');
            $this->llenar('treboles');
        
        }

        private function llenar($palo){

            for ($i = 1; $i <= 13; $i++) {
                $carta;
                if($i <= 10 ){
                    $carta = new Carta($palo,$i.'',$i);
                }else if($i === 11){
                    $carta = new Carta($palo,'J',10);
                }else if($i === 12){
                    $carta = new Carta($palo,'Q',10);
                }else{
                    $carta = new Carta($palo,'K',10);
                }
                array_push($this->cartas, $carta);
            }

        }

        function reiniciar(){}        

    }

    class Espanyola extends Baraja{
        
        function __construct(){

            $this->llenar('copas');
            $this->llenar('espadas');
            $this->llenar('bastos');
        
        }

        private function llenar($palo){

            for ($i = 1; $i <= 13; $i++) {
                $carta;
                if($i <= 10 ){
                    $carta = new Carta($palo,$i.'',$i);
                }else if($i === 11){
                    $carta = new Carta($palo,'SOTA',10);
                }else if($i === 12){
                    $carta = new Carta($palo,'CABALLO',10);
                }else{
                    $carta = new Carta($palo,'REY',10);
                }
                array_push($this->cartas, $carta);
            }
            
        }

    
        function reiniciar(){}        

    }

    class Mus{

        public $baraja;

        public $jugador1;
        public $jugador2;
        public $jugador3;
        public $jugador4;

        function __construct($baraja,$jugador1,$jugador2,$jugador3,$jugador4){

            $this->baraja = $baraja;
            $this->jugador1 = $jugador1;
            $this->jugador2 = $jugador2;
            $this->jugador3 = $jugador3;
            $this->jugador4 = $jugador4;

        }

        public function jugar(){

            $this->baraja->barajar();

            for ($i = 1; $i <= 4; $i++) {

                $this->jugador1->cogerCarta($this->baraja->repartir());
                $this->jugador2->cogerCarta($this->baraja->repartir());
                $this->jugador3->cogerCarta($this->baraja->repartir());
                $this->jugador4->cogerCarta($this->baraja->repartir());

            }

        }

        public function pares($cartasJugador){

            $valores = [];


            foreach($cartasJugador as $carta){
                array_push($valores, $carta->valor);
            }

            $valores_sinrepetir = array_unique($valores);
            $comunes = array_diff_assoc($valores,$valores_sinrepetir);
            $repetidos = array_unique($comunes);
            
            switch(count($repetidos)){
                case 2:
                        return 'Duplex';
                        break;
                case 1:
                        if(count(array_unique($valores)) == 2){

                            return 'Medias';

                        }else{

                            return 'Pares';

                        }
                        break;
                default:
                        return "Nada";
                        break;                  
            }
            

        }

        public function puntos($cartasJugador){
            
            $puntuacion = 0;
            
            foreach ($cartasJugador as $carta) {
                $puntuacion = $puntuacion + $carta->valor;
            }

            return $puntuacion;

        }
        

    }

    class Poker{

        public $baraja;

        public $jugadores;

        function __construct($baraja, $jugadores){

            $this->baraja = $baraja;

            if(count($jugadores) >= 2){

                $this->jugadores = $jugadores;

            }else{

                throw  new Exception('No puedes jugar solo');

            }
        }

        public function jugar(){

            $this->baraja->barajar();

            $numjugadores = count($this->jugadores);
            
            for ($i = 1; $i <= 5; $i++) {

                for($x = 0; $x <= ($numjugadores-1); $x++){

                    $this->jugadores[$x]->cogerCarta($this->baraja->repartir());
                   
                }
                
            }

        }
        public function jugada($cartasJugador){

            $valores = [];

            foreach($cartasJugador as $carta){
                array_push($valores, $carta->valor);
            }

            $valores_sinrepetir = array_unique($valores);
            $comunes = array_diff_assoc($valores,$valores_sinrepetir);
            $repetidos = array_unique($comunes);
            
            switch(count($repetidos)){
                case 2:
                        return 'Doble Pareja';
                        break;
                case 1:
                        if(count($valores_sinrepetir) == 3){

                            return 'Trio';

                        }else if(count($valores_sinrepetir) == 4){

                            return 'Pareja';

                        }else{
                            return'Nada';
                        }
                        break;
                default:
                        return "Nada";
                        break;                  
            }

        }
        public function puntos($cartasJugador){
            
            $puntuacion = 0;
            
            foreach ($cartasJugador as $carta) {
                $puntuacion = $puntuacion + $carta->valor;
            }

            return $puntuacion;

        }

    }

    /**BARAJAS */
    $francesa = new Francesa();
    $espanyola = new Espanyola();

    /**JUGADORES */
    $jugador1 = new Jugador('jugador1');
    $jugador2 = new Jugador('jugador2');
    $jugador3 = new Jugador('jugador3');
    $jugador4 = new Jugador('jugador4');

    $jugadorPoker1 = new Jugador('jugador1');
    $jugadorPoker2 = new Jugador('jugador2');

    /**JUEGOS */
    echo'Empieza el juego MUS<br>';

    $mus = new Mus($francesa,$jugador1,$jugador2,$jugador3,$jugador4);
    
    echo 'REPARTIENDO ...<br>';
    $mus->jugar();
    
    echo 'JUGADOR 1 ha conseguido '.$mus->pares($jugador1->cartas).' y su puntuacion es de '.$mus->puntos($jugador1->cartas).' puntos<br>';
    echo 'JUGADOR 2 ha conseguido '.$mus->pares($jugador2->cartas).' y su puntuacion es de '.$mus->puntos($jugador2->cartas).' puntos<br>';
    echo 'JUGADOR 3 ha conseguido '.$mus->pares($jugador3->cartas).' y su puntuacion es de '.$mus->puntos($jugador3->cartas).' puntos<br>';
    echo 'JUGADOR 4 ha conseguido '.$mus->pares($jugador4->cartas).' y su puntuacion es de '.$mus->puntos($jugador4->cartas).' puntos<br>';
    echo '<br>';
    echo'Empieza el juego POKER<br>';

    $jugadores = [$jugadorPoker1,$jugadorPoker2];

    $poker = new Poker($espanyola,$jugadores);

    echo 'REPARTIENDO ...<br>';
    $poker->jugar();

    echo 'JUGADOR 1 ha tirado '.$poker->jugada($jugadorPoker1->cartas).' y su puntuacion es de '.$poker->puntos($jugadorPoker1->cartas).' puntos<br>';
    echo 'JUGADOR 2 ha tirado '.$poker->jugada($jugadorPoker2->cartas).' y su puntuacion es de '.$poker->puntos($jugadorPoker2->cartas).' puntos<br>';
   