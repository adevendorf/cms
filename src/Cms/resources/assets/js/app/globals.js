export default {
  init() {

    window.jsonOut = function(obj){
      return JSON.parse(JSON.stringify(obj));
    };
    
    window.cropper = {};
  }

}