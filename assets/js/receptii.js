function generateReceptie(){

}
function initGlobalVars(){
    els.templateReceptie= $('#template_receptie');
}
function bindEvents(){
    $('#receptie9').click(generateReceptie)[0].tva= 9;
    $('#receptie24').click(generateReceptie)[0].tva= 24;
}
function pageLoaded($){
    window.els= {};
    initGlobalVars();
    bindEvents();
}
jQuery.load(pageLoaded);
