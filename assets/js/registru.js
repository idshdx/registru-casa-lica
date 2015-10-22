/***
 * utility functions
 */
function jso2string(jso) {
    return JSON.stringify(jso, null, '\t');
}
//(debug) display JSON data received from server at the beginning of BODY
function printJSO(JSO) {
    displayDiv= $('#jso_debug_print');
    if(displayDiv.length) displayDiv.empty();
    else displayDiv=$('<div id="jso_debug_print"></div>').prependTo($('body'));
    $('<pre>' + jso2string(JSO) + '</pre>').appendTo(displayDiv);
}
//calls a callback function func with parameters "value"[, "memberName"[, "object"]] for each member of "object"
function forEachMember( Obj, func){
    Object.keys(Obj).forEach(function(key){
        func(Obj[key], key, Obj);
    });
}

function subtractDPDecimals(dpDec1, dpDec2){
    return (dpDec1*100 - dpDec2*100)/100;
}

/***
 * functions that retrieve / send data from / to the server
 */
//returns output from URL request - synchronous function
function remote(URL){
    return $.ajax({ type: "GET", url: URL, async: false}).responseText;
}
function recordUpdated(data) {
    updateFurnizori();
    printJSO(serverData);
    //alert(data); //debugging
    var record= JSON.parse(data);
    var editRow;
    forEachMember( tables, function(tb) {
        if(!(editRow && editRow.length)) editRow= tb.children('tr.input_row.hidden');
    });
    var recTableBody= editRow.parent();
    var displayRow= templateRow().clone().removeClass('template_row').insertAfter(editRow);
    displayRow[0].dbid= record.ID; //set record id to custom DOM TR property dbid
    setRowValues(displayRow, serverData.furnizori[currentTableType][record.IDFurnizor],
        record.Factura, record.Chitanta, decimal(record.Suma));
    editRow.remove();
    updateTotals();
}
function requestRecordUpdate() {
    var tr= $(this).parent().parent();
    var record= recordFromInputRow(tr);
    tr.addClass('hidden');
    currentTableType= tr.parent().parent()[0].id.substr(6);
    $.post( '../index.php/action/edit_record/Sume' + currentTableType + '/' + tr[0].dbid + '/' + serverData.zi.ID,
        record, recordUpdated );
}
function recordsReturned(jso){
    serverData= JSON.parse(jso);
    populatePage();
}
function datePicked(dateChange){

    //display the selected date in the title
    datatitlu.text(datePicker.data('DateTimePicker').viewDate().format('DD.MM.YYYY'));
    //(datePicker.datetimepicker('getFormattedDate'));


    $.post('../index.php/table/get-records-json/'+datePicker.data('DateTimePicker').viewDate().format('YYYY-MM-DD'),
        null, recordsReturned);
}
function updateFurnizori(){
    serverData.furnizori= JSON.parse(remote('../index.php/table/get-furnizori-json'));
    updateFurnizoriDatalists();
}
//ajax callback for requestNewRecord
function recordAdded(data) {

    //get updated "furnizori" data from server
    updateFurnizori();

    //set the target method's "this" to "currentTableType" (record-table ID without prefix)
    displayRecord.call( currentTableType, JSON.parse(data) );
    clearInputRowValues(tables[currentTableType]);
    updateTotals();
}
//sends the data for a new record for the 3 similar 4-column vertical tables
function requestNewRecord() {
    //console.log('posting marfa');
    var tr = $(this).parent().parent();
    window.currentTableType = tr.parent().parent()[0].id.substr(6);
    //alert(jso2string(serverData.furnizori['MarfaTVA9']));
    var data= { 'Furnizor': inputRowVal(tr, 0), 'Factura': inputRowVal(tr, 1),
        'Chitanta': inputRowVal(tr, 2), 'Suma': inputRowVal(tr, 3), 'IDZi': serverData.zi.ID };
    //alert(tip+'\n'+jso2string(data));
    clearInputRowValues(tables[currentTableType]);
    $.post('../index.php/action/add_record/Sume'+currentTableType, data, recordAdded);
}
//ajax callback: new date added
function endDayDone(data){

    //received date string won't contain leading zeroes, so reconstruct a Date object
    newLastDay = JSON.parse(data).new_last_day.split('-');
    newLastDay = new Date(newLastDay[0], newLastDay[1]-1, newLastDay[2]);

    //update the datepicker to allow selecting the new day
    datePicker.data('DateTimePicker').maxDate(newLastDay);
}
function endDay(){
    end_day.remove();
    $.post('../index.php/table/new_day', null, endDayDone);
}
function getTotals(){
    serverData.totals= JSON.parse(remote('../index.php/table/get-total-json/'+serverData.zi.ID));
}
function displayAport(jsoAport){
    aportTemplate.clone().insertBefore(inputAport.parent())
        .removeClass('template_cell')
        .text(decimal(jsoAport.Suma))
        [0].dbid= jsoAport.ID;
}
function aportAdded(data){
    //alert(data); //debugging
    newAport= JSON.parse(data);
    serverData.Aport.push(newAport);
    displayAport(newAport);
}
function addAport(){
    newAport= inputAport.val();
    inputAport.val('');
    $.post('../index.php/action/add_aport/'+serverData.zi.ID +'/' + newAport, null, aportAdded);
}
function updateTotals(){
    getTotals();
    displayTotals();
}

/***
 * DOM retrieval
 */
function templateRow() {
    return tables['MarfaTVA9'].children('tr.template_row');
}
function inputRow(tb) {
    return tb.children('tr.input_row').last();
}
function recordFromInputRow(row) {
    var inputs= row.children('td').children('input');
    var record={  }; // { ID: row[0].dbid }
    record['Furnizor']= inputs.eq(0).val();
    var value= inputs.eq(1).val();
    if(value!='') record['Factura']= value;
    value= inputs.eq(2).val();
    if(value!='') record['Chitanta']= value;
    record['Suma']= inputs.eq(3).val();
    return record;
}
//retrieves nth input's value from "tr" (jQuery .input_row object)
function inputRowVal(tr, nth) {
    return tr.children('td').eq(nth).children('input').val();
}
function aportCells(){
    return trAport.children('td.display_cell');
}

/***
 * DOM manipulation
 */
//exchange a display row with an editable row having the display row's values
function toggleEdit() {
    var row= $(this).parent().parent();
    //don't edit two rows at a time for now
    //if(row.parent().children('tr.input_row').length>1) return;
    var newrow= inputRow(row.parent()).clone().insertAfter(row);
    newrow[0].dbid= row[0].dbid;
    newrow.find('button').last().click(requestRecordUpdate);
    var cells= newrow.children('td');
    setInputValues(cells, row.children('td'), [0,1,2,3]);
    row.remove();
}
function setInputValues(inputCells, sourceCells, indices) {
    indices.forEach(function(cellIndex){
        inputCells.eq(cellIndex).children('input').val(sourceCells.eq(cellIndex).text());
    });
}
//sets values for table cells "td" in display (non-input) rows
function setRowValues(row) {
    //assign each cell a value received as function argument (following "row")
    $.each(arguments, function(i, val) {
        //traverse args ignoring first; when i=1, set td with id 0, and so on
        if(i>0) row.children('td').eq(i-1).text(val);
    });
}
function setRowValue(tr, tdi, val){
    tr.children('td').eq(tdi).text(val);
}
function addDataRow(table) {
    addedRow = templateRow(table).clone().removeClass('template_row').insertBefore(inputRow(table));
    addedRow.find('button').last().click(toggleEdit);
    return addedRow;
}
//add a "record" (display) row with given record (object) values
function displayRecord(record) {
    var tip= this; //"this" points to a string representing the ID of the BODY's parent table minus the "table_" prefix
    var addedRow= addDataRow(tables[tip]);
    setRowValues(addedRow, serverData.furnizori[tip][record.IDFurnizor],
        record.Factura, record.Chitanta, decimal(record.Suma));
    addedRow[0].dbid= record.ID;
}
function clearInputRowValues(tb){
    inputRow(tb).find('input').val(null);
}
//clear display (record) rows from record tables - used before populating the tables
function clearRecordTables(){
    forEachMember(tables, function(table){
        table.children('tr:not(.input_row):not(.template_row):not(.totals_row)').remove();
    });
}
//update text of TD elements of table "cumuli" with the data passed from the server
function displayCumuli(){
    cumuli.eq(0).text(decimal(serverData.cumuli.soldinitial));
    cumuli.eq(1).text(decimal(serverData.cumuli.Cheltuieli));
    cumuli.eq(2).text(decimal(serverData.cumuli.MarfaTVA24));
    cumuli.eq(3).text(decimal(serverData.cumuli.MarfaTVA9));
    cumuli.eq(4).text(decimal(serverData.cumuli.Aport));
}
function displayTotals(){
    var totalPlati= 0;
    forEachMember(tables, function(table, tip){
        setRowValue(table.children('tr.totals_row'), 1, serverData.totals[tip]);
        totalPlati+= serverData.totals[tip];
    });
    $('#total_Aport').text(serverData.totals.Aport);
    $('#total_plati').text(totalPlati);
    $('#total_sold_curent').text(serverData.cumuli.soldinitial + serverData.totals.Aport);
    //in JS it is necessary to get rid of decimals in order for the subtraction to work as expected
    var soldFinal= subtractDPDecimals(serverData.cumuli.soldinitial + serverData.totals.Aport, totalPlati);
    $('#total_sold_final').text(soldFinal);
}
function displayAporturi(){
    //clear current aportCells
    aportCells().remove();
    //display all aporturi in serverData.Aport
    serverData.Aport.forEach(function(jsoAport){
        displayAport(jsoAport);
    });
}
//populate page tables, etc. with data
function populatePage(){
    clearRecordTables();
    forEachMember(tables, function(table, tip){
        serverData[tip].forEach(displayRecord, tip); //'tip' will be accessible as 'this'
    });
    displayAporturi();
    displayCumuli();
    datatitlu.text(serverData.zi.Data);
    displayTotals();
}
function setupDates(){

    window.datePicker= $('#datetimepicker1');
    window.dateInput= datePicker.children('input');

    //allow selecting only dates registered in the db
    var minDate= serverData.first_date.split('-'), maxDate= new Date(serverData.zi.Data);
    minDate= new Date(minDate[0], minDate[1]-1, minDate[2]);

    //set date picker date format
    datePicker.datetimepicker( {locale: 'ro', format: 'DD.MM.YYYY', defaultDate: maxDate} );

    //disallow picking an invalid (not registered with the db) date
    datePicker.data("DateTimePicker").minDate(minDate).maxDate(maxDate);


    //setup datepicked change callback
    datePicker.on("dp.change", datePicked);
    //datePicker.data("DateTimePicker").date(maxDate);

    datatitlu.text(maxDate.toLocaleDateString('ro-RO')); //(dateInput.val());
    //datePicked(); //update the title date
}
function updateFurnizoriDatalist(tip){
    var furnizor;
    Object.keys(serverData.furnizori[tip]).forEach(function(IDFurnizor){
        furnizor= serverData.furnizori[tip][IDFurnizor];
        datalists[tip].append('<option value="'+furnizor+'">');
    });
}
function updateFurnizoriDatalists(){
    Object.keys(tables).forEach(function(tip){
        datalists[tip].empty();
        updateFurnizoriDatalist(tip);
    });
}
//update the input autocomplete lists
function constructDatalistsFurnizori(){
    window.datalists= {};
    Object.keys(tables).forEach(function(tip){
        datalist= $('#furnizori_'+tip);
        datalists[tip]= datalist;
        datalist.empty();
        updateFurnizoriDatalist(tip);
    });
}

/***
 * data & logic
 */
function decimal(number) {
    return number.toFixed(2);
}
function getTables(){
    window.tables= {};
    ['MarfaTVA9', 'MarfaTVA24', 'Cheltuieli'].forEach(function(tipPlata){
        tables[tipPlata]= $('#tabel_'+tipPlata).children('tbody');
    });
}

function pageLoaded($) {

    window.datatitlu= $('#datatitlu');
    setupDates();

    window.end_day= $('#end_day').click(endDay); //the bottom button

    window.cumuli= $('#cumuli').children('td');

    getTables();
    window.trAport= $('#tabel_Aport').children('tbody:first').children('tr:first');
    window.inputAport= $('#input_aport');
    window.aportTemplate= trAport.children('td.template_cell:first');

    //console.log(datalists); //debugging

    populatePage();
    forEachMember( tables, function(table){
        table.find('button').last().click(requestNewRecord);
    });
    $('#new_aport').click(addAport);

    constructDatalistsFurnizori();

    printJSO(serverData);
    console.log(window.serverData);
}
jQuery(pageLoaded);