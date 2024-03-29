<?php
declare(strict_types=1);

/*
 * 420dw3project WebpageRoute.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-26
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\GivenCode\Domain;

use Teacher\GivenCode\Exceptions\ValidationException;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-03-26
 */
class WebpageRoute extends AbstractRoute {
    
    private string $webpagePath;
    
    public function __construct(string $uri, string $webpage_path) {
        parent::__construct($uri);
        $webpage_path = PRJ_PAGES_DIR . $webpage_path;
        if (!file_exists($webpage_path)) {
            throw new ValidationException("WebpageRoute specified file at path [$webpage_path] does not exists.");
        }
        $this->webpagePath = $webpage_path;
    }
    
    public function route() : void {
        include $this->webpagePath;
    }
}