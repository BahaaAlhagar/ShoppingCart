<?php

namespace BahaaAlhagar\ShoppingCart;

use Illuminate\Filesystem\Filesystem;

/**
 * Helper functions for the ShoppingCart commands.
 **/

class packageHelper
{
	
	/**
     * The filesystem handler.
     * @var object
     */
    public $files;

    /**
     * Create a new instance.
     * @param Illuminate\Filesystem\Filesystem $files
     */
	public function __construct(Filesystem $files)
	{
		$this->files = $files;
	}

	/**
     * Open haystack, find and replace needles, save haystack.
     *
     * @param  string $oldFile The haystack
     * @param  mixed  $search  String or array to look for (the needles)
     * @param  mixed  $replace What to replace the needles for?
     * @param  string $newFile Where to save, defaults to $oldFile
     *
     * @return void
     */
    public function replaceAndSave($oldFile, $search, $replace, $newFile = null)
    {
        $newFile = ($newFile == null) ? $oldFile : $newFile;
        $file = $this->files->get($oldFile);
        $replacing = str_replace($search, $replace, $file);
        $this->files->put($newFile, $replacing);
    }
    
    /**
     * composer dump autoloads.
     * 
     * @return mixed
     */
    public function dumpAutoloads()
    {
        shell_exec('composer dump-autoload');
    }
}

