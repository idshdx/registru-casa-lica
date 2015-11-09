
// an object that stores values associated with a (jQuery) DOM element
// we set this object as a custom DOM property, for persistence between function calls
function customData(element){
    if(!element[0].customData) element[0].customData= {};
    return element[0].customData;
}
function formatCurrency(nmbr){
    return nmbr.toFixed(2);
}
function inputToText(){
    var td= $(this);
    var input= td.children('input[type="text"]');
    if( (!input.length) || input.length==0 ) return;
    var val= input.val();
    input.remove();
    if( td.hasClass('currency') )
        td.text( formatCurrency( val.trim()=='' ? 0 : parseFloat(val) ) );
    else td.text(val);
}
function textToInput(){
    var el= $(this);
    var val= el.text();
    $(els.inputTemplate).appendTo(el.text('')).val(val);
}
function hide(el){
    el.addClass('hidden');
}
function show(el){
    el.removeClass('hidden');
}
function display(el, visible){
    if(visible) show(el); else hide(el);
}
function acceptButton(tr){
    return tr.children('td.action').children('button.btn-success');
}
function editButton(tr){
    return tr.children('td.action').children('button.btn-primary');
}
function acceptHeader(){
    hide(acceptButton(els.headerRow));
    els.headerRow.children('td:not(.action)').each(inputToText);
    show(editButton(els.headerRow));
}
function editHeader(){
    hide(editButton(els.headerRow));
    els.headerRow.children('td:not(.action)').each(textToInput);
    show(acceptButton(els.headerRow));
}
function initGlobals(){
    window.els= {
        headerRow: $('#header-data-row'),
        inputTemplate: '<input type="text" class="form-control usr-input button">',
        templateAviz: '<tbody>' + $('#avize').children('tbody').first().html() + '<tbody>',
        totalGeneral: $('#aviz-total'),
        totalGenRow: $('#aviz-total-row')
    };
    window.gb= {};
}
function updateTotGen(){

}
function updateTotals(avizTBody){
    updateTotGen();
}
function editRow(){
    var tr= $(this).parent().parent();
    hide(editButton(tr));
    tr.children('td:not(.action)').each(textToInput);
    show(acceptButton(tr));
}
function acceptChanges(){
    var tr= $(this).parent().parent();
    hide(acceptButton(tr));
    tr.children('td').has('input').each(inputToText);
    show(editButton(tr));
    updateTotals(tr.parent())
}
function newAviz(inputRow){

}
function avizEmpty(inputRow){

}
function acceptRow(){
    var inputRow= $(this).parent().parent();
    newRow= inputRow.clone().removeClass('input-row');
    var newFurnizor= newRow.children('td').has('input').children('input').eq(0).val().trim();
    if(newFurnizor){

    }
    var aviz= inputRow.parent();
    if(newFurnizor) aviz= $(els.templateAviz).insertAfter(aviz);
    newRow.insertBefore( aviz.children('tr.input-row') );
    hide( acceptButton(newRow).off('click').click(acceptChanges).click() );
    show( editButton(newRow).click(editRow) );
}
function pageLoaded($){
    initGlobals();
    acceptButton(els.headerRow).click(acceptHeader);
    editButton(els.headerRow).click(editHeader);
    acceptButton( $('#avize').children('tbody').first().children('tr.input-row') ).click(acceptRow);
}
jQuery(pageLoaded);
