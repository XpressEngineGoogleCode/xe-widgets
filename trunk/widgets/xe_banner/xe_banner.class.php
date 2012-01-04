<?php
    /**
     * @class xe_news
     * @author zero (zero@nzeo.com)
     * @brief XE공식사이트의 배너 위젯
     * @version 0.1
     **/

    class xe_banner extends WidgetHandler {

        /**
         * @brief 위젯의 실행 부분
         *
         * ./widgets/위젯/conf/info.xml 에 선언한 extra_vars를 args로 받는다
         * 결과를 만든후 print가 아니라 return 해주어야 한다
         **/
        function proc($args) {
            //number of the image
            //$imageNo = 5;
			
            $oModuleModel = &getModel('module');
			$output = $oModuleModel->getModuleFileBoxList();
            $imgSrcs = explode(",",$args->images);
            foreach($output->data as $image){
            	if(in_array($image->filename, $imgSrcs)) $images[]=$image;
            }
            $imageNo = count($images);
            
            //height,width
            $intRE = '/[0-9]+/msi';
            $widget_info->imgHeight = '396';
            if(preg_match($intRE,$args->banner_height)){
   				$widget_info->imgHeight = $args->banner_height;
            }
            $widget_info->imgWidth = '958';
            if(preg_match($intRE,$args->banner_width)){
   				$widget_info->imgWidth = $args->banner_width;
            }

            // add images
            $widget_info->info = array();
            foreach($images as $i => $image){
            	$widget_info->info[$i]['image'] = $image->filename;
            	$widget_info->info[$i]['title'] = $image->attributes["title"];
            	$widget_info->info[$i]['description'] = $image->attributes["description"];
            	$widget_info->info[$i]['url'] = $image->attributes["url"];
            }
            //no image add default images
            if(!count($widget_info->info)){
            	$widget_info->is_default = true;
            	for($i=1;$i<=3;$i++){
            		$key = 'banner_'.$i;
            		$widget_info->info[$i]['url'] = 'http://www.xpressengine.com';
		        	$widget_info->info[$i]['image'] = getUrl().$this->widget_path.'images/'.'defaultImg'.$i.'.jpg';
            	}
            }

            //css name
            $widget_info->classPre = rand();
            Context::set('widget_info', $widget_info);

            // set template path
            $tpl_path = sprintf('%sskins/%s', $this->widget_path, $args->skin);

            // set skin file
			if (in_array(Context::get('act'), array("procWidgetGenerateCodeInPage", "dispPageAdminContentModify")))
                $tpl_file = 'pageedit';
            else
                $tpl_file = 'banner';
            // set skin
            $oTemplate = &TemplateHandler::getInstance();
            return $oTemplate->compile($tpl_path, $tpl_file);
        }
    }