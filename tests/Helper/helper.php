<?php

namespace PRGANYAR\MVC\TEST\App{

    function header(string $value){
        echo $value;
    }

}

namespace PRGANYAR\MVC\TEST\Session{

    function setcookie(string $name, string $value){
        echo "$name: $value";
    }
}