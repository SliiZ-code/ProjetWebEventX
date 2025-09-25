<?php

class Router {
    private $routes = [];
    
    public function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }
    
    public function get($path, $handler) { $this->addRoute('GET', $path, $handler); }
    public function post($path, $handler) { $this->addRoute('POST', $path, $handler); }
    public function put($path, $handler) { $this->addRoute('PUT', $path, $handler); }
    public function delete($path, $handler) { $this->addRoute('DELETE', $path, $handler); }
    
    public function dispatch($method, $currentPath) {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->pathMatches($route['path'], $currentPath)) {
                $params = $this->extractParams($route['path'], $currentPath);
                return call_user_func($route['handler'], $params);
            }
        }
        
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Route not found']);
    }
    
    private function pathMatches($routePath, $currentPath) {
        $routeParts = explode('/', trim($routePath, '/'));
        $currentParts = explode('/', trim($currentPath, '/'));
        
        if (count($routeParts) !== count($currentParts)) return false;
        
        for ($i = 0; $i < count($routeParts); $i++) {
            if (!str_starts_with($routeParts[$i], '{') && $routeParts[$i] !== $currentParts[$i]) {
                return false;
            }
        }
        return true;
    }
    
    private function extractParams($routePath, $currentPath) {
        $routeParts = explode('/', trim($routePath, '/'));
        $currentParts = explode('/', trim($currentPath, '/'));
        $params = [];
        
        for ($i = 0; $i < count($routeParts); $i++) {
            if (str_starts_with($routeParts[$i], '{') && str_ends_with($routeParts[$i], '}')) {
                $paramName = trim($routeParts[$i], '{}');
                $params[$paramName] = $currentParts[$i];
            }
        }
        return $params;
    }
}
?>