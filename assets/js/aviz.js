
maxRows= 38;
// an object that stores values associated with a (jQuery) DOM element
// we set this object as a custom DOM property, for persistence between function calls
function customData(element){
    if(!element[0].customData) element[0].customData= {};
    return element[0].customData;
}
function formatCurrency(nmbr){
    return nmbr.toFixed(2);
}
//calculates the sum over the provided TRs at the specified column index (zero based, or negative (-1 means last)
function colSum(TRs, colIndex){
    if(!TRs.length) return 0;
    var sum= 0, cells, cIndex;
    for(var r= 0; r < TRs.length; r++){
        cells= TRs.eq(r).children('td');
        cIndex= colIndex;
        if(colIndex<0) cIndex+= cells.length;
        sum+= parseFloat( cells.eq(cIndex).text() );
    }
    return sum;
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
    $(els.inputTemplate).appendTo(el.text('')).val(val).keydown(passFocusToNextInputCell);
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
function passFocusToNextInputCell(eventData){
    if(eventData.keyCode==40) {
        if($(this).parent().nextAll().has('input').length)
            $(this).parent().nextAll().has('input').first().children('input').focus();
        else
            $(this).parent().nextAll().has('button').first().children('button').focus();
    }
}
function nthChild(tr, index){
    var els= tr.children();
    return els.eq( (index<0 ? els.length : 0) + index )
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
        totalGeneral: $('#total'),
        totalGenRow: $('#total-row'),
        subtotalRowTemplate: $('#avize').children('tr.subtotal-row').clone().removeClass('hidden')[0].outerHTML
    };
    window.gb= {};
}
function updateTotal(){
    subtotalRows= $('#avize').children('tr.subtotal-row');
    els.totalGeneral.text( formatCurrency(colSum(subtotalRows, -1)) );
}
function updateSubtotal(tr){
    var datarows= rowSectionDataRows(tr);
    var subttotalDisplay= datarows.last().next().next().children(':not(.action)').last();
    subttotalDisplay.text( formatCurrency(colSum(datarows,-2)) );
    updateTotal();
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
    //assign cell on column 'Valoarea' the result of Pret * Cantitate
    nthChild(tr, -2).text( formatCurrency( parseFloat(nthChild(tr,-4).text()) * parseFloat(nthChild(tr,-3).text()) ) );
    show(editButton(tr));
    updateSubtotal(tr);
}
function isNewSection(inputRow){
    return !( inputRow.prev().hasClass('data-row') );
}
function clearChildInput(){
    $(this).children('input').val('');
}
function rowSectionDataRows(datarow){
    var prevDataRows= datarow.prevUntil(':not(.data-row)');
    var nextDataRows= datarow.nextUntil(':not(.data-row)');
    return prevDataRows.add(datarow).add(nextDataRows);
}
function updateRowSpan(inputOrDataRow){
    sectionDataRows=  rowSectionDataRows(inputOrDataRow);
    furnizorCell= sectionDataRows.first().children('td').first();
    furnizorCell.attr('rowspan', sectionDataRows.length);
}
function appendDataRow(inputRow){
    var newDataRow= inputRow.clone().removeClass('input-row button').addClass('data-row').insertBefore(inputRow);
    if(!isNewSection(newDataRow)) newDataRow.children('td').eq(0).remove();
    editButton(newDataRow).click(editRow);
    acceptButton(newDataRow).off('click').click(acceptChanges).click();
    updateRowSpan(newDataRow);
    inputRow.children('td').has('input').each(clearChildInput);
}
function sectionsCount(){
    return $('#avize').children('tr.subtotal-row').length;
}
function newSection(inputRow){
    var newAvizInputRow= inputRow.clone().insertAfter(inputRow.next());
    inputRow.children('td').has('input').each(clearChildInput);
    $(els.subtotalRowTemplate).insertAfter(newAvizInputRow);
    newAvizInputRow.children('td').children('input').keydown(passFocusToNextInputCell);
    appendDataRow( newAvizInputRow );
    acceptButton(newAvizInputRow).off('click').click(acceptRow);
    newAvizInputRow.children().first().children().focus();
    //remove the default empty section when using it to generate (clone) the first section (by completing a furnizor)
    if( $('#avize').children('tr').first().hasClass('input-row') ) {
        $('#avize').children('tr').first().remove(); //the input-row
        $('#avize').children('tr').first().remove(); //the subtotal-row
    }
    if(sectionsCount() == 2) $('#avize').children('tr.subtotal-row').removeClass('hidden');
}
function acceptRow(){
    var inputRow= $(this).parent().parent();
    var newFurnizor= inputRow.children('td').has('input').eq(0).children('input').focus().val().trim();
    //ignore first insert if a furnizor is not provided
    //var dataRowsCount= $('#avize').children('tr.data-row').length;
    if(!( $('#avize').children('tr.data-row').length || newFurnizor )) return;
    //whenever a furnizor is provided, create a section (with its own provider and subtotal)
    if(newFurnizor) newSection(inputRow);
    else appendDataRow(inputRow);

    inputRow.children().first().children().focus();
}
function pageLoaded($){
    initGlobals();
    acceptButton(els.headerRow).click(acceptHeader);
    editButton(els.headerRow).click(editHeader);
    acceptButton( $('#avize').children('tr.input-row') ).click(acceptRow);
    //make inputs pass focus to next input in row when user presses the down arrow key.
    $('#avize').children('tr.input-row').children('td').children('input').keydown(passFocusToNextInputCell);
}
jQuery(pageLoaded);
