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
function templateRow(TBody) {
    return TBody.children('tr.template_row');
}
function inputRow(TBody) {
    return TBody.children('tr.input_row').last();
}
function recordUpdated(data) {
    //alert(data); //debugging
    var record= JSON.parse(data);
    var editRow;
    forEachMember( tables, function(table) {
        if(!(editRow && editRow.length)) editRow= table.children('tr.input_row.hidden');
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
function displayRecord(record) {
    var tip= this;
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
//ajax callback for requestNewRecord
function recordAdded(data) {
    displayRecord(JSON.parse(data));
}
//sends the data for a new record for the 3 similar 4-column vertical tables
function requestNewRecord() {
    console.log('posting marfa');
    var tr = $(this).parent().parent();
    var tip = tr.parent().parent()[0].id.substr(6);
    //alert(jso2string(serverData.furnizori['MarfaTVA9']));
    var data= { 'Furnizor': inputRowVal(tr, 0), 'Factura': inputRowVal(tr, 1),
        'Chitanta': inputRowVal(tr, 2), 'Suma': inputRowVal(tr, 3), 'IDZi': serverData.zi.ID };
    alert(tip+'\n'+jso2string(data));
    $.post('../index.php/action/add_record/Sume'+tip, data, recordAdded);
}
//populate page tables, etc. with data
function popPage(){
    Object.keys(tables).forEach(function(tip){
        tables[tip].children('tr:not(.template_row):not(.input_row):not(.totals_row)').remove();
        serverData[tip].forEach(displayRecord, tip); //'tip' will be accessible as 'this'
    });
    cumuli.eq(0).text(decimal(serverData.cumuli.soldinitial));
    cumuli.eq(1).text(decimal(serverData.cumuli.total_chelt));
    cumuli.eq(2).text(decimal(serverData.cumuli.total_tva24));
    cumuli.eq(3).text(decimal(serverData.cumuli.total_tva9));
    cumuli.eq(4).text(decimal(serverData.cumuli.total_aport));
    datatitlu.text(serverData.zi.Data);
}
function getTables(){
    window.tables= {};
    tables['MarfaTVA9']= {};
    tables['MarfaTVA24']= {};
    tables['Cheltuieli']= {};
    Object.keys(tables).forEach(function(tip){
        tables[tip]= $('#tabel_'+tip).children('tbody');
    });
}
function forEachMember( Obj, func){
    Object.keys(Obj).forEach(function(key){
        func(Obj[key], key, Obj);
    });
}
function datePicked(dateChange){
    datatitlu.text(datePicker.data('DateTimePicker').viewDate().format('LLL'));
    //(datePicker.datetimepicker('getFormattedDate'));
}
function setupDates(){

    window.datePicker= $('#datetimepicker1');
    window.dateInput= datePicker.children('input');

    //allow selecting only dates registered in the db
    var minDate= serverData.first_date.split('-'), maxDate= new Date(serverData.zi.Data);
    minDate= new Date(minDate[0], minDate[1]-1, minDate[2]);

    //set date picker date format
    datePicker.datetimepicker( {locale: 'ro', format: 'DD.MM.YYYY', defaultDate: maxDate} );

    datePicker.data("DateTimePicker").minDate(minDate).maxDate(maxDate);


    //setup datepicked change callback
    datePicker.on("dp.change", datePicked);
    //datePicker.data("DateTimePicker").date(maxDate);

    datatitlu.text(maxDate.toLocaleDateString('ro-RO')); //(dateInput.val());
    //datePicked(); //update the title date
}
function endDayDone(data){
    newLastDay = JSON.parse(data).new_last_day.split('-');
    newLastDay = new Date(newLastDay[0], newLastDay[1]-1, newLastDay[2]);
    datePicker.data('DateTimePicker').maxDate(newLastDay);
}
function endDay(){
    $.post('../index.php/table/new_day', null, endDayDone);
}
function pageLoaded($) {

    window.datatitlu= $('#datatitlu');
    setupDates();

    window.end_day= $('#end_day').click(endDay);

    window.cumuli= $('#cumuli').children('td');

    getTables();

    popPage();
    forEachMember( tables, function(table){
        table.find('button').last().click(requestNewRecord);
    });

    printJSO(serverData);
}
jQuery(pageLoaded);