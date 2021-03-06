<?php
	namespace Shell\Lib;

	Class ShellAutoloader{
		static public function commands($className){
			if(preg_match_all('/^Shell\\\\Command\\\\([^\\\\]+)\\\\(.+)$/i', $className, $matches) == 1){
				$command = $matches[2][0];
				$extension = $matches[1][0];
				$file = EXTENSIONS . "/{$extension}/bin/{$command}";
				if (is_readable($file)) {
					require $file;
				}
			}

			return;
		}

		static public function library($className){

			$className = ltrim($className, '\\');
			$file = $namespace = NULL;

			if ($lastNsPos = strripos($className, '\\')) {
				$namespace = substr($className, 0, $lastNsPos);
				$className = substr($className, $lastNsPos + 1);
				$path  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
			}

			$path = EXTENSIONS . DIRECTORY_SEPARATOR . strtolower($path);

			// Class
			$file = str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
			if(is_readable($path . DIRECTORY_SEPARATOR . $file)){
				require $path . DIRECTORY_SEPARATOR . $file;
				return;
			}

		}

		static public function extensionDriver($className){

			if(!preg_match_all('/^Extension_(.*)$/i', $className, $matches)) return;

			$extension = strtolower($matches[1][0]);
			$file = EXTENSIONS . DIRECTORY_SEPARATOR . $extension . DIRECTORY_SEPARATOR . "extension.driver.php";
			if(is_readable($file)){
				require $file;
			}

			return;
		}
	}

	spl_autoload_register(__NAMESPACE__ . "\ShellAutoloader::commands");
	spl_autoload_register(__NAMESPACE__ . "\ShellAutoloader::library");
	spl_autoload_register(__NAMESPACE__ . "\ShellAutoloader::extensionDriver");

