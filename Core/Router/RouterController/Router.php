<?php

namespace Core;

use MiddlewareSecurity\Security;

@session_start();
class Router
{
    /**
     * @var $requestUrl
     */
    private $requestUrl;

    private $paramsInUrl;

    private $registeredUrl;

    /**
     * @return mixed
     */
    public function getRequestUrl()
    {
        return $this->requestUrl;
    }

    /**
     * @param mixed $requestUrl
     */
    public function setRequestUrl($requestUrl)
    {
        $this->requestUrl = $requestUrl;
    }

    /**
     * @return mixed
     */
    public function getParamsInUrl()
    {
        return $this->paramsInUrl;
    }

    /**
     * @param mixed $paramsInUrl
     */
    public function setParamsInUrl($paramsInUrl)
    {
        $this->paramsInUrl = $paramsInUrl;
    }

    /**
     * @return mixed
     */
    public function getRegisteredUrl()
    {
        return $this->registeredUrl;
    }

    /**
     * @param mixed $registeredUrl
     */
    public function setRegisteredUrl($registeredUrl)
    {
        $this->registeredUrl = $registeredUrl;
    }

    public static function addView($viewData = []){

        try {
            if(empty($viewData)){
                return false;
            }

            //start making the view here
            $listUri = explode('/', $_SERVER['REQUEST_URI']);
            $baseRoot = $_SERVER['DOCUMENT_ROOT'];
            $middler = "";
            if(in_array('Views', $listUri)){
                $middler = "";
            }else{
                $middler = count($listUri) > 1 ? $listUri[count($listUri) - 2] : "";
            }

            $additionalPath = $middler.'/Core/Router/Register/';
            $completePath = empty($middler) ? $baseRoot.'/Views' : $baseRoot.'/'.$middler.'/Views';
            $completePath .= $viewData['path'][0] === '/' ? $viewData['path'] : "/".$viewData['path'];
            $relativePath = '/Views';
            $relativePath .= $viewData['path'][0] === '/' ? $viewData['path'] : "/".$viewData['path'];
            $storage = $baseRoot.'/'.$additionalPath.'registered_path_available.json';
            $storage = str_replace('//','/',$storage);

            $listAssoc = json_decode(is_file($storage) ? file_get_contents($storage) : json_encode(["status"=>"no-file"]),true);
            if(isset($listAssoc['status']) && $listAssoc['status'] === 'no-file'){
                return false;
            }

            $accessModifies = ['public', 'private'];

            if(!isset($viewData['access']) && in_array($viewData['access'], $accessModifies) === false){
                return "Please ensure access key is added and has public or private value";
            }

            $viewFormat =[
                "view_name"=>$viewData['name'],
                "view_url"=>$viewData['url'],
                "view_path_absolute"=>$completePath,
                "view_path_relative"=>$relativePath,
                "view_timestamp"=>time(),
                "view_description"=>$viewData['description'],
                "view_role_access"=>$viewData['access']
            ];

            foreach ($listAssoc as $item){
                if($item['view_url'] === $viewData['url']) {
                    return "Ensure url is unique and try again";
                }
            }

            array_push($listAssoc, $viewFormat);
            $content = json_encode($listAssoc);

            //clear up

            $content = Router::clearUrl($content);
            if(file_exists($completePath)){
                return "View file name already exist in view directory!";
            }

            if(file_put_contents($completePath, Router::boilerpulate($viewData['path']))){
                if(file_put_contents($storage, $content)){
                    return "View created";
                }else{
                    unlink($completePath);
                    return "Failed to create view";
                }
            }
        }catch (\Exception $e){
            return $e->getMessage();
        }

    }

    public static function clearUrl($content){

        if(!empty($content)){
            $content = str_replace("\\", "", $content);
            $content = str_replace('\/'," ",$content);
            $content = str_replace('/', '/', $content);
            return $content;
        }
    }

    public static function boilerpulate($view){

        $list = explode('.', $view);
        $extension = strtolower(end($list));
        switch ($extension){
            case 'html':
                return "<section>{$list[0]}</section>";
            case 'php':
                return "<?php @session_start(); ?>";
            default:
                return "add your code here .... valid for {$extension}";
        }
    }

    public static function router($restricstionLevel = false, $status){

        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $host = parse_url($_SERVER['REQUEST_URI'], PHP_URL_HOST);
        $query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        parse_str($query, $list);
        $queryList = isset($list) ? $list : [];

        $path = $path[strlen($path)-1] === '/' ? substr($path, 1, strlen($path) - 2) : substr($path, 1, strlen($path));

        $storage = 'Core/Router/Register/registered_path_available.json';
        $foundView = [];
        if(file_exists($storage)){
            $views = json_decode(file_get_contents($storage), true);
            $_SESSION['viewsstorage'] = $views;
            foreach($views as $view){
              if(strtolower($path) === strtolower($view['view_url'])){
                  $foundView = $view;
                  break;
              }
            }
            $data = [
                "host"=>$host,
                "path"=>$path,
                "query"=>$query,
                "params"=>$queryList,
                "view"=>$foundView
            ];
            $_SESSION['public_data']=$data;
            if(!empty($foundView)){

                if($restricstionLevel === true){
                    $security = new Security();
                    $access = $security->checkViewAccess();

                    if($access === "V-NULL"){
                        $_SESSION['message']['route'] = "Page looking for is unreacheble at moment";
                    }elseif($access === "V-PRIVATE"){
                        $user = $security->checkCurrentUser();
                        if($user === "U-Admin"){
                            $_SESSION['access']['role'] = 1;
                            self::requiringFile($foundView);
                        }elseif($user === "U-BLOCKED"){
                            $_SESSION['message']['route'] = "Sorry your account is blocked by authority if this is misunderstand please contact administrator";
                        }elseif ($user === "V-VERIFIED"){
                            self::requiringFile($foundView);
                        }else{
                            $_SESSION['message']['route'] = "Page is private your are not allowed here";
                        }
                    }else{
                       self::requiringFile($foundView);
                    }
                }else{
                    self::requiringFile($foundView);
                }
            }else{
               $_SESSION['message']['route'] ="404 view not found with url ({$path} ) in system visit <a href='creating-view'>Create view</a> also you can view list of all your views registered visit <a href='my-views'>Views list</a>";
            }

        }else{
            echo "View not found";
            exit;
        }
    }

    public static function requiringFile($foundView = []){
        http_response_code(200);
        $list = explode('.', $foundView['view_path_absolute']);
        $contetType = Router::headerContentType(end($list));
        require_once $foundView['view_path_absolute'];
    }

    public static function headerContentType($extension){
        switch ($extension){
            case 'html':
                return 'text/html';
            case 'php':
                return 'txt/html';
            case 'json':
                return 'application/json';
            case 'xml':
                return 'application/xml';
            case 'js':
                return 'application/javascript';
            default:
                return 'plain/text';

        }
    }
}