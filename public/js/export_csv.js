function convertArrayOfObjectsToCSV(args) {
	var result, ctr, keys, columnDelimiter, lineDelimiter, data;
	console.log(args);
	
	for(i=0; i< args.data.length;i++){
		// console.log(args.data);
		delete args.data[i]['uid'];
	}
	
	data = args.data || null;
		if (data == null || !data.length) {
			return null;
		}

	columnDelimiter = args.columnDelimiter || ',';
	lineDelimiter = args.lineDelimiter || '\n';

	keys = Object.keys(data[0]);
	console.log(keys);
	result = '';
	result += keys.join(columnDelimiter);
	result += lineDelimiter;
	
	data.forEach(function(item) {
		ctr = 0;
		keys.forEach(function(key) {
			if (ctr > 0) result += columnDelimiter;
				if(item[key]==null){
					item[key]=0;
				}
				var temp = item[key].toString();
				// console.log(temp);
				// temp = temp.replace(/[\r\n]/g,'');
				// console.log(temp);
				temp = temp.replace(/,/g,';');
				result += temp;
				ctr++;
			});
		result += lineDelimiter;
	});
	// console.log(result);
	return result;
}

function downloadCSV(filename, data) {
	var data, filename, link;

	var csv = convertArrayOfObjectsToCSV({
		data: data
	});
	// console.log(data);
	if (csv == null) return;
	// console.log(csv);
	filename = filename || 'export.csv';

	// if (!csv.match(/^data:text\/csv/i)) {
		// csv = 'data:text/csv;charset=utf-8,' + csv;
	// }
	
	// data = encodeURI(csv);
	// console.log(data);


	var link = document.createElement('a');
	var blob = new Blob(["\ufeff", csv], {
         type: "text/csv;charset=utf-8"
    });
	var url = URL.createObjectURL(blob);
	link.href = url;
	link.setAttribute('download', filename);
	document.body.appendChild(link);
	link.click();
	console.log(link);
}