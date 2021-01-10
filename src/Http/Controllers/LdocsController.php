<?php

namespace Mdemet\Ldocs\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use Mdemet\Ldocs\Models\LdocsClassType;
use Mdemet\Ldocs\Models\LdocsClassNamespace;
use Mdemet\Ldocs\Models\LdocsClass;
use Mdemet\Ldocs\Models\LdocsClassMethod;

use Illuminate\Http\Request;

class LdocsController extends Controller
{
    //


    public function index() {
        $class_types = LdocsClassType::all();

        // foreach($class_types as $class_type) {
        //     echo "<h1>" . $class_type->name . "</h1>";
        //     foreach ($class_type->namespaces as $namespace) {
        //         echo "<h2>" . $namespace->name . "</h2>";
        //         foreach ($namespace->classes as $class) {
        //             echo "<h3>" . $class->name . "</h3>";
        //             echo "<ul>";
        //             foreach ($class->methods as $method) {
        //                 echo "<li>" . $method->name. "</li>";
        //             }
        //             echo "</ul>";

        //         }
        //     }
        // }

        return view("ldocs::index", ['class_types' => $class_types]);
    }

    public function ajaxSave(Request $request) {
        if ($request->type == "class") {
            LdocsClass::where('id', $request->id)->update(['description' => $request->description]);
        }
        else if ($request->type == "method") {
            LdocsClassMethod::where('id', $request->id)->update(['description' => $request->description]);
        }        
        return response()->json("success");
    }



    public function discoverClasses() {

        // get a list of all the classes
        $composer_classes = require_once base_path('vendor/composer/autoload_classmap.php');
        $composer_classes = array_keys($composer_classes);



        /* CONTROLLERS AND ROUTES */

        $controllers = array_filter($composer_classes, function ($controller) {
            return (((strpos($controller, 'App\Http\Controllers') !== false) || (strpos($controller, 'Modules') === 0)) && (strpos($controller, 'Controllers') !== false));
        });

        // create an array with controller names as key and list of methods as values
        $all_controllers_and_functions = [];
        foreach ($controllers as $key => $controller_path) {
            $controller_functions = array_diff(get_class_methods($controller_path), get_class_methods("App\Http\Controllers\Controller"));
            if (isset($controller_functions[0]) && $controller_functions[0] == "__construct") unset($controller_functions[0]);
            $controller_functions = array_values($controller_functions);

            $controller_path_parts = explode('\\', $controller_path);
            $controller = $controller_path_parts[count($controller_path_parts) - 1];
            $namespace = str_replace($controller, "", $controller_path);

            $all_controllers_and_functions[$namespace][$controller] = $controller_functions;
        }

        unset($all_controllers_and_functions["App\Https"]); // remove Controller.php
        ksort($all_controllers_and_functions);
        // dump($all_controllers_and_functions);

        // get a list of all routes and extract controllers and functions
        $route_controllers_and_functions = [];
        foreach (Route::getRoutes()->getRoutes() as $route) {
            $action = $route->getAction();

            if (array_key_exists('controller', $action)) {
                if (preg_match('/^App/', $action['controller'])) { // filter controllers under the "App" namespace

                    $action_parts = explode('@', $action['controller']);
                    $controller_path = $action_parts[0];
                    $function = $action_parts[1];

                    $controller_path_parts = explode('\\', $controller_path);

                    $controller = $controller_path_parts[count($controller_path_parts) - 1];
                    $namespace = str_replace($controller, "", $controller_path);


                    $route_controllers_and_functions[$namespace][$controller][$function] = $route->uri;
                }
            }
        }
        ksort($route_controllers_and_functions);

        // combine controller functions and routes
        $combined_controllers_and_functions = [];
        foreach ($all_controllers_and_functions as $ns => $controllers) {
            foreach ($controllers as $controller => $functions) {
                foreach ($functions as $i => $function) {
                    $url = (isset($route_controllers_and_functions[$ns][$controller][$function])) ? $route_controllers_and_functions[$ns][$controller][$function] : "-";
                    $combined_controllers_and_functions[$ns][$controller][$function] = $url;

                    $controller_class_type = $this->getOrCreateClassType("Controllers");
                    $controller_class_namespace = $this->getOrCreateClassNamespace($controller_class_type->id, $ns);
                    $controller_class = $this->getOrCreateClass($controller_class_namespace->id, $controller);
                    $controller_class_method = $this->getOrCreateClassMethod($controller_class->id, $function, $url);
                }
            }
        }



        /* MODELS */

        // get models in the App\ folder
        $models1 = array_filter($composer_classes, function ($class) {
            return ((strpos($class, 'App\\') !== false) && (count(explode('\\', $class)) < 3));
        });
        sort($models1);

        // get models in App\Models folder
        $models2 = array_filter($composer_classes, function ($class) {
            return (((strpos($class, 'App\Models') !== false) || (strpos($class, 'Modules') === 0)) && (strpos($class, 'Models') !== false));
        });
        sort($models2);

        $all_models = array_merge($models1, $models2);

        $this->saveData("Models", $all_models);


        /* NOTIFICATIONS */

        $items = array_filter($composer_classes, function ($class) {
            return (((strpos($class, 'App\Notifications') !== false) || (strpos($class, 'Modules') === 0)) && (strpos($class, 'Notifications') !== false));
        });
        sort($items);
        $this->saveData("Notifications", $items);


        /* EVENTS */

        $items = array_filter($composer_classes, function ($class) {
            return (((strpos($class, 'App\Events') !== false) || (strpos($class, 'Modules') === 0)) && (strpos($class, 'Events') !== false));
        });
        sort($items);
        $this->saveData("Events", $items);


        /* LISTENERS */

        $items = array_filter($composer_classes, function ($class) {
            return (((strpos($class, 'App\Listeners') !== false) || (strpos($class, 'Modules') === 0)) && (strpos($class, 'Listeners') !== false));
        });
        sort($items);
        $this->saveData("Events", $items);


        /* PROVIDERS */

        $items = array_filter($composer_classes, function ($class) {
            return (((strpos($class, 'App\Providers') !== false) || (strpos($class, 'Modules') === 0)) && (strpos($class, 'Providers') !== false));
        });
        sort($items);
        $this->saveData("Providers", $items);


        return redirect()->route('ldocs-index');

    }






    /* PRIVATE FUNCTIONS */


    private function saveData($class_type, $items) {
        foreach ($items as $item) {
            $item_path_parts = explode("\\", $item);
            $item_name_clean = $item_path_parts[count($item_path_parts) - 1];
            $item_namespace = str_replace($item_name_clean, "", $item);
            $item_class_type = $this->getOrCreateClassType($class_type);
            $item_class_namespace = $this->getOrCreateClassNamespace($item_class_type->id, $item_namespace);
            $item_class = $this->getOrCreateClass($item_class_namespace->id, $item_name_clean);

            $methods = get_class_methods($item);
            foreach ($methods as $method) {
                if ($method == "__construct") { // stop reading item methods after construct
                    break;
                }
                $item_class_method = $this->getOrCreateClassMethod($item_class->id, $method);
            }
        }
    }

    private function getOrCreateClassType($name)
    {
        $class_type = LdocsClassType::where('name', $name)->first();
        if (!$class_type) {
            $class_type = new LdocsClassType();
            $class_type->name = $name;
            $class_type->save();
        }
        return $class_type;
    }
    private function getOrCreateClassNamespace($type_id, $name)
    {
        $class_namespace = LdocsClassNamespace::where('ldocs_class_type_id', $type_id)->where('name', $name)->first();
        if (!$class_namespace) {
            $class_namespace = new LdocsClassNamespace();
            $class_namespace->name = $name;
            $class_namespace->ldocs_class_type_id = $type_id;
            $class_namespace->save();
        }
        return $class_namespace;
    }
    private function getOrCreateClass($namespace_id, $name)
    {
        $class_namespace = LdocsClass::where('ldocs_class_namespace_id', $namespace_id)->where('name', $name)->first();
        if (!$class_namespace) {
            $class_namespace = new LdocsClass();
            $class_namespace->name = $name;
            $class_namespace->ldocs_class_namespace_id = $namespace_id;
            $class_namespace->save();
        }
        return $class_namespace;
    }
    private function getOrCreateClassMethod($class_id, $name, $url = "")
    {
        $class_method = LdocsClassMethod::where('ldocs_class_id', $class_id)->where('name', $name)->first();
        if (!$class_method) {
            $class_method = new LdocsClassMethod();
            $class_method->name = $name;
            $class_method->url = $url;
            $class_method->ldocs_class_id = $class_id;
            $class_method->save();
        }
        return $class_method;
    }


}
