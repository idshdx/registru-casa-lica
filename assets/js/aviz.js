
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
        inputTemplate: '<input class="form-control usr-input button" type="text">',
        templateAviz: $('#template-aviz')
    };
    window.gb= {};
}
function pageLoaded($){
    initGlobals();
    acceptButton(els.headerRow).click(acceptHeader);
    editButton(els.headerRow).click(editHeader);
}
jQuery(pageLoaded);
