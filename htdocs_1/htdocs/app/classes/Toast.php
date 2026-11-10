<?php

class Toast {
    
    private $valid_types = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'];
    
    private $default = 'primary';
    
    private $type;
    private $msg;
    
    /**
     * @param string|array $msg 
     * @param string $type 
     * @return bool
     */
    public static function new($msg, $type = null) {
        $self = new self();
        
        if($type === null) {
            $self->type = $self->default;
        }
        
        $self->type = in_array($type, $self->valid_types) ? $type : $self->default;
        
        if(is_array($msg)) {
            foreach ($msg as $m) {
                $_SESSION[$self->type][] = $m;
            }
            return true;
        }
        
        $_SESSION[$self->type][] = $msg;
        return true;
    }
    
    /**
     * @return string 
     */
    public static function flash() {
        $self = new self();
        $output = '';
        
        foreach ($self->valid_types as $type) {
            if(isset($_SESSION[$type]) && !empty($_SESSION[$type])) {
                foreach ($_SESSION[$type] as $m) {
                    $output .= '<div class="alert alert-'.$type.' alert-dismissible fade show" role="alert">';
                    $output .= $m;
                    $output .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>';
                    $output .= '</div>';
                }
                unset($_SESSION[$type]);
            }
        }
        
        return $output;
    }
}
