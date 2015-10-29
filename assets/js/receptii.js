function hide(el){
    el.addClass('hidden');
}
function show(el){
    el.removeClass('hidden');
}
function see(el, visible){
    if(visible) show(el); else hide(el);
}
function initGlobalVars(){
    els.templateReceptie= $('#template_receptie');
    els.endOfReceptii= $('#end_of_receptii');
    els.inputTemplate= $('#template-input').html();
}
// assigns a "customData" custom property to the jQuery object's associated DOM element
function inputToText(){
    var td= $(this);
    var input= td.children('input[type="text"]');
    if( (!input.length) || input.length==0 ) return;
    var val= input.val();
    input.remove();
    td.text(val);
}
function textToInput(){
    var el= $(this);
    var val= el.text();
    $(els.inputTemplate).appendTo(el.text('')).val(val);
}
// an object that stores values associated with a (jQuery) DOM element
// we set this object as a custom DOM property, for persistence between function calls
function customData(element){
    if(!element[0].customData) element[0].customData= {};
    return element[0].customData;
}
function editButton(tr){
    return tr.children('td.action').children('button.btn-primary');
}
function acceptButton(tr){
    return tr.children('td.action').children('button.btn-success');
}
function editRow(){
    tr= $(this).parent().parent();
    tr.children('td:not(.action)').each(textToInput);
    show(acceptButton(tr));
    hide(editButton(tr));
}
function acceptChanges(){
    var tr= $(this).parent().parent();
    tr.children('td').each(inputToText);
    hide(acceptButton(tr));
    show(editButton(tr));
}
function clearInputs(tr){
    tr.children('td:not(.action)').children('input').val('');
}
function acceptRow(){
    var trInput= $(this).parent().parent();
    var tr= trInput.clone().insertBefore(trInput);
    editButton(tr).click(editRow);
    acceptButton(tr).click(acceptChanges).click();
    clearInputs(trInput);
}
function editHeader(){
    var tr= $(this).parent().parent();
    tr.children('td:not(.action)').each(textToInput);
    hide(editButton(tr));
    show(acceptButton(tr));
}
//will be called on "add receptie" dropdown <LI> click
function generateReceptie(){
    var receptie= els.templateReceptie.clone().removeAttr('id').insertBefore(els.endOfReceptii);
    var tva= customData($(this)).tva;
    customData(receptie).tva= tva;
    receptie.find('.procentaj_tva').text(tva);
    show(els.endOfReceptii);
    var headerEdit= editButton(receptie.find('.action-accept-header').click(acceptChanges).parent().parent());
    headerEdit.click(editHeader);
    receptie.find('button.btn-success:not(.action-accept-header)').click(acceptRow);
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
