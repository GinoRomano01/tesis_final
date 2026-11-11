<?php
class Toast {
    private $valid_types = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'];

    private $default = 'primary';

    private $type;
    private $msg;

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
    public static function flash() {
        $self = new self();
        $output = '';

        $iconos = [
            'success' => 'fa-circle-check',
            'danger'  => 'fa-circle-xmark',
            'warning' => 'fa-triangle-exclamation',
        ];

        foreach ($self->valid_types as $type) {
            if(isset($_SESSION[$type]) && !empty($_SESSION[$type])) {
                $clase = in_array($type, ['success', 'danger', 'warning']) ? $type : 'info';
                $icono = $iconos[$type] ?? 'fa-circle-info';
                foreach ($_SESSION[$type] as $m) {
                    $output .= '<div class="sp-toast sp-toast--' . $clase . '" role="' . ($clase === 'danger' ? 'alert' : 'status') . '">';
                    $output .= '<i class="fas ' . $icono . ' sp-toast-icono" aria-hidden="true"></i>';
                    $output .= '<div class="sp-toast-msg">' . $m . '</div>';
                    $output .= '<button type="button" class="sp-toast-cerrar" aria-label="Cerrar">&times;</button>';
                    $output .= '</div>';
                }
                unset($_SESSION[$type]);
            }
        }

        if ($output === '') return '';
        return '<div class="sp-toasts" aria-live="polite">' . $output . '</div>'
             . '<script>(function(){'
             . 'document.querySelectorAll(".sp-toast:not([data-ok])").forEach(function(t){'
             . 't.setAttribute("data-ok","1");'
             . 'var ms=t.classList.contains("sp-toast--danger")?9000:5000,timer;'
             . 'function cerrar(){t.classList.add("sp-toast--saliendo");setTimeout(function(){t.remove();},250);}'
             . 'function programar(){timer=setTimeout(cerrar,ms);}'
             . 't.querySelector(".sp-toast-cerrar").addEventListener("click",cerrar);'
             . 't.addEventListener("mouseenter",function(){clearTimeout(timer);});'
             . 't.addEventListener("mouseleave",programar);'
             . 'programar();});'
             . '})();</script>';
    }
}
