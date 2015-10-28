// assigns a "customData" custom property to the jQuery object's associated DOM element
function inputToText(){

}
function customData(element){
    if(!element[0].customData) element[0].customData= {};
    return element[0].customData;
}
//will be called on "add receptie" dropdown li click
function generateReceptie(){
    var receptie= els.templateReceptie.clone().removeAttr('id').insertBefore(els.endOfReceptii);
    var tva= customData($(this)).tva;
    customData(receptie).tva= tva;
    receptie.find('.procentaj_tva').text(tva);
    els.endOfReceptii.removeClass('hidden');
}
function initGlobalVars(){
    els.templateReceptie= $('#template_receptie');
    els.endOfReceptii= $('#end_of_receptii');
}
function bindEvents(){
    customData( $('#receptie9').click(generateReceptie) ).tva= 9;
    customData( $('#receptie24').click(generateReceptie) ).tva= 24;
}
function pageLoaded($){
    window.els= {};
    initGlobalVars();
    bindEvents();
}
jQuery(pageLoaded);
