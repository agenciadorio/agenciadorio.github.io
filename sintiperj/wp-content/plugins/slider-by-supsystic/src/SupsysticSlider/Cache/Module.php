<?php

class SupsysticSlider_Cache_Module extends SupsysticSlider_Core_BaseModule
{
	private $extension = '.html';
	private $cacheDirectory;

	public function onInit()
	{
		$this->cacheDirectory = $this->getConfig()->get('plugin_sliders_cache') . DIRECTORY_SEPARATOR;
	}

	public function get($filename) {

		$filePath = $this->getFullPath($filename);
		if ($this->cacheDirectory && file_exists($filePath) && $this->getEnvironment()->isProd()) {
			return file_get_contents($filePath);
		}

		return false;
	}

	public function set($filename, $data) {
		return file_put_contents($this->getFullPath($filename), $data);
	}

	public function clean($filename) {

		$filePath = $this->getFullPath($filename);

		if (file_exists($filePath)) {
			unlink($filePath);
		}
	}

	public function cleanAll() {
		$cacheDir = $this->cacheDirectory;
		if ($cacheDir) {
			array_map('unlink', glob("{$cacheDir}*"));
		}
	}

	private function getFullPath($filename) {
		return $this->cacheDirectory . $filename . $this->extension;
	}
}