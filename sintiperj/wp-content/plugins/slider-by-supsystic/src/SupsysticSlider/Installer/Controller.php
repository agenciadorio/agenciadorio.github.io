<?php


class SupsysticSlider_Installer_Controller extends SupsysticSlider_Core_BaseController
{
    public function askUninstallAction()
    {
        $request = $this->getRequest();

        //Uncomment to enable deactivation dialog
        /*if ($request->query->has('drop')) {
            if ('Yes' == $request->query->get('drop')) {
                return true;
            } else {
                return false;
            }
        }

        return $this->getEnvironment()
            ->getTwig()
            ->render(
                '@installer/uninstall.twig',
                array(
                    'request' => $this->getRequest(),
                )
            );*/

        return false;
    }

    public function cleanCacheAction()
    {
        if (current_user_can('manage_options')) {                                                     
            $this->cleanDirRecursive($this->getConfig()->get('plugin_twig_cache'));
        }

        print 'Done';
    }

    private function cleanDirRecursive($dir) {
        if (!$dir || !is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), array('.', '..'));

        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->cleanDirRecursive("$dir/$file") : @unlink("$dir/$file"); 
        } 
        return @rmdir($dir); 
    }
} 