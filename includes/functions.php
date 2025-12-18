
<?php

// Fonction 1: Nettoyer input (sécurité XSS)
function clean($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Fonction 2: Afficher message
function showMessage($type, $message) {
    $colors = [
        'success' => 'green',
        'error' => 'red',
        'warning' => 'yellow'
    ];
    
    $color = $colors[$type];
    
    echo "<div style='padding:10px; background-color:light{$color}; color:{$color}; margin:10px 0; border-radius:5px;'>
            {$message}
          </div>";
}

// Fonction 3: Formater date
function formatDate($date) {
    return date('d/m/Y H:i', strtotime($date));
}

// Fonction 4: Couper texte
function cutText($text, $length = 100) {
    if (strlen($text) > $length) {
        return substr($text, 0, $length) . '...';
    }
    return $text;
}

?>