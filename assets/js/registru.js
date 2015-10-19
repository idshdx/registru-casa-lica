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
    alert(TBody[0].id + ' ' +TBody.children('tr.template_row').length);
    return TBody.children('tr.template_row');
}
function inputRow(table) {
    return table.children('tr.input_row').last();
}
function recordUpdated(data) {
    alert(data);
    var record= JSON.parse(data);
    var editRow;
    forEachMember( tables, function(table) {
        if(!(editRow && editRow.length)) editRow= table.children('tr.input_row.hidden');
    });
    var recTableBody= editRow.parent();
    var displayRow= templateRow(recTableBody).clone().removeClass('template_row').insertAfter(editRow);
    displayRow[0].dbid= record.ID;
    setRowValues(displayRow, record.Furnizor, record.Factura, record.Chitanta, record.Suma);
    editRow.remove();
}
function postUpdateRecord() {
    var tr= $(this).parent().parent();
    var record= recordFromInputRow(tr);
    tr.addClass('hidden');
    var tip= tr.parent().parent()[0].id.substr(6);
    $.post( '../index.php/action/edit_record/Sume'+tip+'/'+tr[0].dbid+'/'+serverData.zi.ID, record, recordUpdated );
}
function toggleEdit() {
    var row= $(this).parent().parent();
    //don't edit two rows at a time for now
    //if(row.parent().children('tr.input_row').length>1) return;
    var newrow= inputRow(row.parent()).clone().insertAfter(row);
    newrow[0].dbid= row[0].dbid;
    newrow.find('button').last().click(postUpdateRecord);
    var cells= newrow.children('td');
    setInputValues(cells, row.children('td'), [0,1,2,3]);
    row.remove();
}
function setInputValues(inputCells, sourceCells, indices) {
    indices.forEach(function(cellIndex){
        inputCells.eq(cellIndex).children('input').val(sourceCells.eq(cellIndex).text());
    });
}
function setRowValues(row) {
    //assign each cell a value received as function argument (following "row")
    $.each(arguments, function(i, val) {
        //traverse args, ignore first. when i=1, set td with id 0, and so on
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
function readRecordRow(record) {
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
function inputRowVal(tr, index) {
    return tr.children('td').eq(index).children('input').val();
}
function furnizor(id, tip) {
    return serverData.furnizori[tip][id];
}
function ajaxReturned(data) {
    readRecordRow(JSON.parse(data));
}
function postMarfa() {
    console.log('posting marfa');
    var tr = $(this).parent().parent();
    var tip = tr.parent().parent()[0].id.substr(6);
    //alert(jso2string(serverData.furnizori['MarfaTVA9']));
    var data= { 'Furnizor': inputRowVal(tr, 0), 'Factura': inputRowVal(tr, 1),
        'Chitanta': inputRowVal(tr, 2), 'Suma': inputRowVal(tr, 3), 'IDZi': serverData.zi.ID };
    alert(tip+'\n'+jso2string(data));
    $.post('../index.php/action/add_record/Sume'+tip, data, ajaxReturned);
}
//populate page
function popPage(){
    Object.keys(tables).forEach(function(tip){
        tables[tip].children('tr:not(.template_row):not(.input_row):not(.totals_row)').remove();
        serverData[tip].forEach(readRecordRow, tip); //'tip' will be accessible as 'this'
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
function pageLoaded($) {

    //set date picker date format
    $('#datetimepicker1').datetimepicker( {locale: 'ro', format: 'DD.MM.YYYY'} );

    cumuli= $('#cumuli').children('td');

    getTables();
    datatitlu= $('#datatitlu');
    printJSO(serverData);

    popPage();
    forEachMember( tables, function(table){
        table.find('button').last().click(postMarfa);
    });
}
jQuery(pageLoaded);