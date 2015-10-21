function jso2string(jso) {
    return JSON.stringify(jso, null, '\t');
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
function templateRow(tb) {
    return tb.children('tr.template_row');
}
function inputRow(tb) {
    return tb.children('tr.input_row').last();
}
function recordUpdated(data) {
    //alert(data); //debugging
    var record= JSON.parse(data);
    var editRow;
    forEachMember( tables, function(tb) {
        if(!(editRow && editRow.length)) editRow= tb.children('tr.input_row.hidden');
    });
    var recTableBody= editRow.parent();
    var displayRow= templateRow(recTableBody).clone().removeClass('template_row').insertAfter(editRow);
    displayRow[0].dbid= record.ID;
    setRowValues(displayRow, record.Furnizor, record.Factura, record.Chitanta, decimal(record.Suma));
    editRow.remove();
}
function requestRecordUpdate() {
    var tr= $(this).parent().parent();
    var record= recordFromInputRow(tr);
    tr.addClass('hidden');
    var tip= tr.parent().parent()[0].id.substr(6);
    $.post( '../index.php/action/edit_record/Sume'+tip+'/'+tr[0].dbid+'/'+serverData.zi.ID, record, recordUpdated );
}
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
function addDataRow(table) {
    addedRow = templateRow(table).clone().removeClass('template_row').insertBefore(inputRow(table));
    addedRow.find('button').last().click(toggleEdit);
    return addedRow;
}
function decimal(number) {
    return number.toFixed(2);
}
function remote(URL){
    return $.ajax({ type: "GET", url: URL, async: false}).responseText;
}
function updateFurnizori(){
    serverData.furnizori= JSON.parse(remote('../index.php/table/get-furnizori-json'));
}
//add a "record" (display) row with given record (object) values
function displayRecord(record) {
    var tip= this; //"this" points to a string representing the ID of the BODY's parent table minus the "table_" prefix
    var addedRow= addDataRow(tables[tip]);
    setRowValues(addedRow, furnizor(record.IDFurnizor, tip),
        record.Factura, record.Chitanta, decimal(record.Suma));
    addedRow[0].dbid= record.ID;
}
//(debug) display JSON data received from server
function printJSO(JSO) {
    $('body').prepend('<div><pre>'+jso2string(JSO)+'</pre></div>');
}
//retrieves nth input's value from "tr" (jQuery .input_row object)
function inputRowVal(tr, nth) {
    return tr.children('td').eq(nth).children('input').val();
}
function furnizor(id, tip) {
    return serverData.furnizori[tip][id];
}
function clearInputRowValues(tb){
    inputRow(tb).find('input').val(null);
}
//ajax callback for requestNewRecord
function recordAdded(data) {

    //get updated "furnizori" data from server
    updateFurnizori();

    //set the target method's "this" to "currentTableType" (record-table ID without prefix)
    displayRecord.call( currentTableType, JSON.parse(data) );
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
//clear display (record) rows from record tables - used before populating the tables
function clearRecordTables(){
    forEachMember(tables, function(table, name){
        table.children('tr:not(.input_row):not(.template_row):not(.totals_row)').remove();
    });
}
//update text of TD elements of table "cumuli" with the data passed from the server
function displayCumuli(){
    cumuli.eq(0).text(decimal(serverData.cumuli.soldinitial));
    cumuli.eq(1).text(decimal(serverData.cumuli.chelt));
    cumuli.eq(2).text(decimal(serverData.cumuli.tva24));
    cumuli.eq(3).text(decimal(serverData.cumuli.tva9));
    cumuli.eq(4).text(decimal(serverData.cumuli.aport));
}
//populate page tables, etc. with data
function populatePage(){
    clearRecordTables();
    Object.keys(tables).forEach(function(tip){
        tables[tip].children('tr:not(.template_row):not(.input_row):not(.totals_row)').remove();
        serverData[tip].forEach(displayRecord, tip); //'tip' will be accessible as 'this'
    });
    displayCumuli();
    datatitlu.text(serverData.zi.Data);
}
function getTables(){
    window.tables= {};
    ['MarfaTVA9', 'MarfaTVA24', 'Cheltuieli'].forEach(function(tableName){
       tables[tableName]={};
    });
    Object.keys(tables).forEach(function(tip){
        tables[tip]= $('#tabel_'+tip).children('tbody');
    });
}
//calls a callback function func with parameters "value"[, "memberName"[, "object"]] for each member of "object"
function forEachMember( Obj, func){
    Object.keys(Obj).forEach(function(key){
        func(Obj[key], key, Obj);
    });
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
function pageLoaded($) {

    window.datatitlu= $('#datatitlu');
    setupDates();

    window.end_day= $('#end_day').click(endDay); //the bottom button

    window.cumuli= $('#cumuli').children('td');

    getTables();

    populatePage();
    forEachMember( tables, function(table){
        table.find('button').last().click(requestNewRecord);
    });

    printJSO(serverData);
}
jQuery(pageLoaded);