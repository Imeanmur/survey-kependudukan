<?php
// Simple admin users store (email => [name, role, password_hash])
// Change these credentials as needed. Passwords are hashed using PASSWORD_DEFAULT.
return [
    'admin@example.com' => [
        'name' => 'Admin',
        'role' => 'Diskominfo',
        'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
    ],
    // Example from the mockup; you can remove this.
    'mdrilanang@gmail.com' => [
        'name' => 'Admin',
        'role' => 'Diskominfo',
        'password_hash' => password_hash('anang2018', PASSWORD_DEFAULT),
    ],
    'muridholmeswatson@gmail.com' => [
        'name' => 'Murid Holmes',
        'role' => 'User',
        'password_hash' => password_hash('zai123', PASSWORD_DEFAULT),
    ],
];
