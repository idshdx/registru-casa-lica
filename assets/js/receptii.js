/* global els */

/***
 * utility functions
 */
function jso2string(jso) {
    return JSON.stringify(jso, null, '\t');
}
function subtractDecimals(dpDec1, dpDec2){
    return (dpDec1*100 - dpDec2*100)/100;
}
function roundCurrency(nmbr){
  return +nmbr.toFixed(2);
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
function sumColumn(TRs, colIndex){
    var sum= 0;
    for(var r= 0; r < TRs.length; r++){
        sum+= parseFloat( TRs.eq(r).children('td').eq(colIndex).text() );
    }
    return sum;
}
function displayCurrency(element, decimal){
    element.text( decimal.toFixed(2) );
}
/**
 * page-specific
 */
function colIndex(colName){
  return els.columns.indexOf(colName);
}
function initGlobalVars(){
    els.receptii= $('#receptii');
    els.templateReceptie= $('#template_receptie');
    els.endOfReceptii= $('#end_of_receptii');
    els.inputTemplate= $('#template-input').html();
    els.columns= ['marfa','um','natCantitate','pretFurnizor','valFurnizor','pretAdaos','valAdaos','pretTVA','valTVA'];
    els.message= $('#message');
}
function cellData(tr, colName){
  return tr.children('td').eq( els.columns.indexOf(colName) ).text();
}
// an object that stores values associated with a (jQuery) DOM element
// we set this object as a custom DOM property, for persistence between function calls
function customData(element){
    if(!element[0].customData) element[0].customData= {};
    return element[0].customData;
}
function bindEvents(){
    customData( $('#receptie9').click(generateReceptie) ).tva= 9;
    customData( $('#receptie24').click(generateReceptie) ).tva= 24;
}
// assigns a "customData" custom property to the jQuery object's associated DOM element
function inputToText(){
    var td= $(this);
    var input= td.children('input[type="text"]');
    if( (!input.length) || input.length==0 ) return;
    var val= input.val();
    input.remove();
    if( td.hasClass('currency') ) displayCurrency( td, val.trim()=='' ? 0 : parseFloat(val) );
    else td.text(val);
}
function textToInput(){
    var el= $(this);
    var val= el.text();
    $(els.inputTemplate).appendTo(el.text('')).val(val);
}
function editButton(tr){
    return tr.children('td.action').children('button.btn-primary');
}
function acceptButton(tr){
    return tr.children('td.action').children('button.btn-success');
}
function calcValues(tr){
    var cells={}, values={}, td, tva= customData(receptieByRow(tr)).tva;
    els.columns.forEach(function(colName, colIndex){
    if( colName.startsWith('val')||colName.startsWith('pret')||colName.startsWith('nat') ){
      td= tr.children('td').eq(colIndex);
      //alert( td.text() + '\n' + parseFloat(td.text()) );
      cells[colName]= td;
      values[colName]= parseFloat( td.text() );
    }
    });
    //alert(jso2string(values));
    values.valFurnizor= roundCurrency( values.natCantitate * values.pretFurnizor );
    values.pretAdaos= roundCurrency( values.pretTVA - values.pretTVA * tva / (100+tva)  );
    values.valAdaos= roundCurrency( values.natCantitate * values.pretAdaos );
    values.valTVA= roundCurrency( values.natCantitate * values.pretTVA );
    displayCurrency( cells.valFurnizor, values.valFurnizor );
    displayCurrency( cells.pretAdaos, values.pretAdaos );
    displayCurrency( cells.valAdaos, values.valAdaos );
    displayCurrency( cells.valTVA, values.valTVA );
}
function acceptHeader(){
    var tr= $(this).parent().parent();
    tr.children('td').has('input').each(inputToText);
    hide(acceptButton(tr));
    show(editButton(tr));
}
function acceptChanges(){
    var tr= $(this).parent().parent();
    tr.children('td').has('input').each(inputToText);
    hide( acceptButton(tr) );
    calcValues(tr);
    show( editButton(tr) );
    displayTotals( tr.parent() );
}
function clearInputs(tr){
    tr.children('td:not(.action)').children('input').val('');
}
function editRow(){
    var tr= $(this).parent().parent();
    var tds= tr.children('td');
    hide( editButton(tr) );
    textToInput.call( tds.eq(0) );
    textToInput.call( tds.eq(1) );
    textToInput.call( tds.eq(2) );
    textToInput.call( tds.eq(3) );
    textToInput.call( tds.eq(7) );
    show( acceptButton(tr) );
}
function receptieByRow(tr){
  return tr.parent().parent().parent();
}
function acceptRow(){
    var trInput= $(this).parent().parent();
    var tr= trInput.clone().insertBefore(trInput).removeClass('button').addClass('display');
    editButton(tr).click(editRow);
    acceptButton(tr).click(acceptChanges).click();
    clearInputs(trInput);
    displayTotals( tr.parent() );
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
    var headerEdit= editButton(receptie.find('.action-accept-header').click(acceptHeader).parent().parent());
    headerEdit.click(editHeader);
    receptie.find('button.btn-success:not(.action-accept-header)').click(acceptRow);
    els.receptii.css('margin-top', 'initial');
    hide(els.message);
}
function displayTotals(tbody){
    var displayRows= tbody.children('tr.display');
    var total5= sumColumn(displayRows, 4);
    var total9= sumColumn(displayRows, 6);
    var total11= sumColumn(displayRows, 8);
    var totalAdaos= subtractDecimals( total9, total5 );
    var totalTVA= subtractDecimals( total11, total9 );
    var totalReceptie= (total5 + totalAdaos + totalTVA);
    displayCurrency( tbody.find('.total5'), total5 );
    displayCurrency( tbody.find('.total9'), total9 );
    displayCurrency( tbody.find('.total11'), total11 );
    displayCurrency( tbody.find('.total-adaos'), totalAdaos );
    displayCurrency( tbody.find('.total-tva'), totalTVA );
    displayCurrency( tbody.find('.total-receptie'), totalReceptie );
}
function pageLoaded($){
    window.els= {};
    initGlobalVars();
    bindEvents();
}
jQuery(pageLoaded);
