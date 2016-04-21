<?php //namespace Cms\Services;
//
//use Auth;
//use DB;
//use Cache;
//use Config;
//use Carbon\Carbon;
//use Cms\Models\Extension;
//use Illuminate\Http\Request;
//
//class CmsService {
//
//    protected $cache;
//    protected $request;
//
//    public function __construct(Request $request)
//    {
//        $this->request = $request;
//    }
//
//    public function blogPath()
//    {
//        return config('cms.blog_path');
//    }
//
//
//
//    public function render($section, $name) {
//        if (! isset($section[$name])) return '';
//        return $section[$name];
//    }
//
//    public function categorySlug($categories, $id)
//    {
//        $category = array_filter($categories, function($cat) use($id) {
//            return $cat['id'] == $id;
//        });
//
//        return $category[1]['slug'];
//    }
//
////    public function image($content, $name) {
////        return $content->render($content, null, $name);
////    }
//
//    public function getSetting($type, $key)
//    {
//        $cacheKey = Config::get('site_id') . ':extension:' . $type . ':' . $key;
//
//        if (!Cache::has($cacheKey)) {
//            $ext = Extension::where('type', $type)
//                ->where('key', $key)
//                ->firstOrFail();
//
//
//            Cache::put($cacheKey, $ext->value, 5);
//
//            return $ext->value;
//        }
//
//        return Cache::get($cacheKey);
//    }
//
//
//}
