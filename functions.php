<?php
/**
 * Astra Child Theme functions and definitions
 */

define( 'CHILD_THEME_ASTRA_CHILD_VERSION', '1.0.0' );

function astra_child_enqueue_styles() {

    
    wp_enqueue_style( 'astra-parent-style', get_template_directory_uri() . '/style.css' );

   
    wp_enqueue_style( 'astra-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('astra-parent-style'),
        CHILD_THEME_ASTRA_CHILD_VERSION
    );
}
add_action( 'wp_enqueue_scripts', 'astra_child_enqueue_styles' );

function mostrar_repos_github() {
    $nombre_usuario = 'GMurit'; //VARIABLE DONDE GUARDO EL NOMBRE DE USUARIO DE  MI GITHUB
    $url = "https://api.github.com/users/$nombre_usuario/repos"; //VARIABLE DONDE GUARDO LA URL DE LA API A MIS REPOSITORIOS

    $args = array(
        'headers' => array(
            'User-Agent' => 'Controlz' //IDENTIFICACIÓN DEL SITIO QUE HACE LA SOLICITUD
        )
    );

    $respuesta = wp_remote_get( $url, $args ); //MEDIANTE UNA FUNCIÓN DE WORDPRESS, PEDIMOS A LA URL LA INFORMACIÓN

    if( is_wp_error($respuesta)){ //SI HAY UN ERROR AL OBTENER UNA RESPUESTA DE GITHUB, LANZO EL MENSAJE DEL RETURN
        return 'No se pudieron obtener los repositorios.';
    }

    $repos = json_decode( wp_remote_retrieve_body( $respuesta ) ); //OBTENGO LOS REPOSITORIOS EN FORMATO JSON Y LO CONVIERTO A UN ARRAY DE OBJETOS CON LA FUNCIÓN

    if( empty( $repos ) ) {//SI NO OBETNEMOS NADA DEL JSON, QUIERE DECIR QUE NO HAY REPOSITORIOS
        return 'No hay repositorios para mostrar.';
    }

    $salida = '<ul class="github-repos">'; //SI TENEMOS EL ARRAY DE RESPOTIRIOS, LO RECORREMOS Y LO MOSTRAMOS
    foreach( $repos as $repo ) {
        $salida .= '<li>';
        $salida .= '<a href="' . esc_url($repo->html_url) . '" target="_blank">' . esc_html($repo->name) . '</a>'; //LIMPIAMOS LA URL DEL REPOSITORIO CON ESC_URL Y LA ABRIMOS EN UNA PESTAÑA NUEVA.
        if( !empty($repo->description) ) {
            $salida .= '<span class="repo-description">' . esc_html($repo->description) . '</span>'; //OBTENEMOS LA DESCRIPCIÓN DEL REPOSITORIO.
        }
        $salida .= '</li>';
    }
    $salida .= '</ul>';

    return $salida;
}
add_shortcode('repos_github', 'mostrar_repos_github'); //FUNCIÓN DE WORDPRESS PARA MOSTRAR LOS REPOSITORIOS CON SHORTCODE EN EL BLOG.
//PRIMER PARAMETRO = ES EL SHORTCODE QUE PONGO EN EL BLOG. SEGUNDO PARÁMETRO = LA FUNCIÓN QUE SE EJECUTA.
?>