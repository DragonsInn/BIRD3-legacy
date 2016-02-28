<?php namespace BIRD3\Support\Modules;

use Caffeinated\Modules\Contracts\RepositoryInterface;
use Illuminate\Config\Repository as Config;
use Illuminate\Filesystem\Filesystem;
use Exception;
use Cache;

class InternalModuleRepository implements RepositoryInterface {

    // Holds the config
    protected $config;

    // Holds the files
    protected $files;

    // Module paths
    protected $path;

    /**
     * Constructor method.
     *
     * @param \Illuminate\Config\Repository      $config
     * @param \Illuminate\Filesystem\Filesystem  $files
     */
    public function __construct(Config $config, Filesystem $files) {
        $this->config = $config;
        $this->files  = $files;
    }

    /**
	 * Get all module basenames
	 *
	 * @return array
	 */
	protected function getAllBasenames() {
		$path = $this->getPath();
        try {
            $collection = collect($this->files->directories($path));
            $basenames  = $collection->map(function($item, $key) {
                return basename($item);
            });
    		return $basenames;
        } catch (\InvalidArgumentException $e) {
            return collect(array());
        }
	}

    /**
	 * Get path for the specified module.
	 *
	 * @param  string $slug
	 * @return string
	 */
	public function getModulePath($slug) {
		$module = studly_case($slug);
		return $this->getPath()."/{$module}/";
	}

    /**
	 * Get modules path.
	 *
	 * @return string
	 */
	public function getPath() {
		return $this->path ?: $this->config->get('modules.path');
	}

    /**
    * Get path of module Composer JSON file.
    *
    * @param  string $module
    * @return string
    */
   protected function getManifestPath($moduleName) {
       $base = $this->getModulePath($moduleName);
       $composer = $base."composer.json";
       $module = $base."module.json";
       if(file_exists($composer)) {
           return $composer;
       } else if(file_exists($module)) {
           return $module;
       } else throw new Exception("Module is missing configuration! <$base>");
   }

   /**
     * Get modules namespace.
     *
     * @return string
     */
    public function getNamespace() {
        return $this->config->get('modules.namespace');
    }

    /**
	* Get all modules.
	*
	* @return Collection
	*/
	public function all() {
		$basenames = $this->getAllBasenames();
		$modules   = collect();
		$basenames->each(function($module, $key) use ($modules) {
            $props = $this->getProperties($module);
            if(!isset($props["description"])) { $props["description"] = "[No discription]"; }
            $props["enabled"] = !isset($props["enabled"]) ? true : $props["enabled"];
            $props["slug"] = $module;
            if(!isset($props["name"])) { $props["name"] = $module; }
			$modules->put($module, $props);
		});
		return $modules->sortBy('slug');
	}

	/**
	* Get all module slugs.
	*
	* @return Collection
	*/
	public function slugs() {
		$slugs = collect();
		$this->all()->each(function($item, $key) use ($slugs) {
            $name = $item['slug'];
			$slugs->push($name);
		});
		return $slugs;
	}

	/**
	 * Get modules based on where clause.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return Collection
	 */
	public function where($key, $value) {
		return $this->all()->where($key, $value);
	}

	/**
	 * Sort modules by given key in ascending order.
	 *
	 * @param  string  $key
	 * @return Collection
	 */
	public function sortBy($key) {
		return $this->all()->sortBy($key);
	}

	/**
	* Sort modules by given key in ascending order.
	*
	* @param  string  $key
	* @return Collection
	*/
	public function sortByDesc($key) {
		return $this->all()->sortByDesc($key);
	}

	/**
	 * Determines if the given module exists.
	 *
	 * @param  string  $slug
	 * @return bool
	 */
	public function exists($slug) {
		return $this->slugs()->contains($slug);
	}

	/**
	 * Returns count of all modules.
	 *
	 * @return int
	 */
	public function count() {
		return $this->all()->count();
	}

	/**
	 * Get a module's properties.
	 *
	 * @param  string $slug
	 * @return Collection|null
	 */
	public function getProperties($slug) {
		if (!is_null($slug)) {
			$module     = studly_case($slug);
			$path       = $this->getManifestPath($module);
			$contents   = $this->files->get($path);
			$collection = collect(json_decode($contents, true));
			if (! $collection->has('order')) {
				$collection->put('order', 9001);
			}
			return $collection;
		}
		return null;
	}

	/**
	 * Get a module property value.
	 *
	 * @param  string $property
	 * @param  mixed  $default
	 * @return mixed
	 */
	public function getProperty($property, $default = null) {
		list($module, $key) = explode('::', $property);
		return $this->getProperties($module)->get($key, $default);
	}

	/**
	* Set the given module property value.
	*
	* @param  string  $property
	* @param  mixed   $value
	* @return bool
	*/
	public function setProperty($property, $value) {
		list($module, $key) = explode('::', $property);
		$module  = strtolower($module);
		$content = $this->getProperties($module);
		if (isset($content[$key])) {
			unset($content[$key]);
		}
		$content[$key] = $value;
		$content       = json_encode($content, JSON_PRETTY_PRINT);
		return $this->files->put($this->getManifestPath($module), $content);
	}

	/**
	 * Get all enabled modules.
	 *
	 * @return Collection
	 */
	public function enabled() { return $this->where('enabled', true); }

	/**
	 * Get all disabled modules.
	 *
	 * @return Collection
	 */
	public function disabled() { return $this->where('enabled', false); }

	/**
	 * Check if specified module is enabled.
	 *
	 * @param  string $slug
	 * @return bool
	 */
	public function isEnabled($slug) {
		return $this->getProperty("{$slug}::enabled") === true;
	}

	/**
	 * Check if specified module is disabled.
	 *
	 * @param  string $slug
	 * @return bool
	 */
	public function isDisabled($slug) {
		return $this->getProperty("{$slug}::enabled") === false;
	}

	/**
	 * Enables the specified module.
	 *
	 * @param  string $slug
	 * @return bool
	 */
	public function enable($slug) {
		return $this->setProperty("{$slug}::enabled", true);
	}

	/**
	 * Disables the specified module.
	 *
	 * @param  string $slug
	 * @return bool
	 */
	public function disable($slug) {
		return $this->setProperty("{$slug}::enabled", false);
	}

    /**
     * Refresh the cache with any newly found modules.
     *
     * @return bool
     */
    public function cache() {
        $cacheFile = storage_path('modules.json');
        $cache     = $this->getCache();
        $modules   = $this->all();
        $collection = collect([]);
        foreach ($modules as $module) {
            $collection->put($module['slug'], true);
        }
        $keys    = $collection->keys()->toArray();
        $merged  = $collection->merge($cache)->only($keys);
        $content = json_encode($merged->all(), JSON_PRETTY_PRINT);
        return $this->files->put($cacheFile, $content);
    }
    
    /**
     * Get the contents of the cache file.
     *
     * The cache file lists all module slugs and their
     * enabled or disabled status. This can be used to
     * filter out modules depending on their status.
     *
     * @return Collection
     */
    public function getCache() {
        $cacheFile = storage_path('modules.json');
        if (! $this->files->exists($cacheFile)) {
            $modules = $this->all();
            $content = [];
            foreach ($modules as $module) {
                $content[$module['slug']] = true;
            }
            $content = json_encode($content, JSON_PRETTY_PRINT);
            $this->files->put($cacheFile, $content);
            return collect(json_decode($content, true));
        }
        return collect(json_decode($this->files->get($cacheFile), true));
    }

    /**
     * Set the given cache key value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return int
     */
    public function setCache($key, $value) {
        $cacheFile = storage_path('modules.json');
        $content   = $this->getCache();
        $content->put($key, $value);
        $content = json_encode($content, JSON_PRETTY_PRINT);
        return $this->files->put($cacheFile, $content);
    }
}
