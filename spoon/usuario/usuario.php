<?php
ini_set('display_errors',1);
/*
 * 30.01.2012
 * Manipulación del objeto usuario
 * 
 */

class Usuario extends DBConexion
{
    private $nomUsu;
    private $emaUsu;
    private $canEmaEncontrados;
    private $passwdUsu;
    private $perfil;
    private $nombre;

    public function getPerfil(){
        return $this->perfil;
    }
    
    public function setNombre($nombre){
        if (SpoonFilter::isString($nombre) && SpoonFilter::isSmallerThan(60, strlen($nombre)))
            $this->$nombre = utf8_decode($nombre);
        else{
            echo "Ha introducido un valor Incorrecto";
            exit();
        }
    }

    public function setNomUsu($nombreUsuario){
        if (SpoonFilter::isString($nombreUsuario) && SpoonFilter::isSmallerThan(50, strlen($nombreUsuario)))
            $this->nomUsu = utf8_decode($nombreUsuario);
        else{
            echo "Ha introducido un valor Incorrecto";
            exit();
        }
    }
    
    
    public function setEmaUsu($emailUsuario) {           
        if (SpoonFilter::isEmail($emailUsuario) && SpoonFilter::isSmallerThan(50, strlen($emailUsuario))){          
                $this->emaUsu = $emailUsuario;     
        }else{            
            echo "Ha introducido un valor Incorrecto  para el correo";
            exit();
        }
    }
    
    
    public function setPasUsu($passwordUsuario) {

        if (SpoonFilter::isSmallerThan(40, strlen($passwordUsuario)) && SpoonFilter::isString($passwordUsuario) && SpoonFilter::isAlphaNumeric($passwordUsuario)){
            $this->passwdUsu = sha1($passwordUsuario);
        }else{
            echo "Introduzca una contraseña correcta, use sólo números y letras";
            exit();
        }
    }
    
    
    private function verifyUserExist() {
            $this->canEmaEncontrados = $this->getNumRows('SELECT usuario FROM usuarios WHERE usuario = ?', $this->nomUsu);
                
            if ($this->canEmaEncontrados == 1){
                return true;
            }else{
                return false;
            }
    }    
    

    public function addUser() {
        if ($this->verifyUserExist()){
            echo "Este usuario ya esta registrado. Por favor intente con otro.";
            return false;
        }else if ($this->verifyEmailExist()===false){
            $aUsers['idusu'] = null ;
            $aUsers['nomusu'] = $this->nomUsu;
            $aUsers['corusu'] = $this->emaUsu;
            $aUsers['pasusu'] = $this->passwdUsu;
            $aUsers['nombre'] = $this->nombre;
            $aUsers['fecregusu'] = date('Y-m-d');
            
            try
            {   
                
               $this->insert('usuarios', $aUsers);
                
                return true;        
            }
            catch (Exception $e){ 
                echo $e->getMessage();
                return false;
            }
        
        }
    }
    
    public function logInUser() {
        if ($this->verifyUserExist()){
            $rs = $this->getRecord('SELECT id_usuario, usuario, nombre, perfil FROM usuarios WHERE usuario = ? AND password = ?', array($this->nomUsu, $this->passwdUsu));
            if ($rs != null){
                session_start();
                $_SESSION['nomUsuario'] = $rs['usuario'];
                $_SESSION['idUsuario'] = $rs['id_usuario'];
                $_SESSION['nombre'] = $rs['nombre'];
                $_SESSION['logged'] = true;
                $this->perfil =  $rs['perfil'];
                $_SESSION['perfil'] = $rs['perfil'];
                return true;                        
            }
            else
            {
                echo "Contraseña Incorrecta. Por favor, verifique e intente nuevamente";
                return false;
            }
        }else{
            echo "Este usuario no esta registrado. Por favor, verifique e intente nuevamente.";
            return false;
        }
   
    }
 
}

?>
