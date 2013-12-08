function build_table(rowTemplate,data) {
	table = "<table>";
	table += "<tr>";
	for(i=0;i<rowTemplate.length;i++) {
		table += "<th>" + rowTemplate[i].name + "</th>";
	}
	table += "</tr>";
	for(i=0;i<data.length;i++) {
		table += "<tr>";
		for(j=0;j<rowTemplate.length;j++) {
			if(rowTemplate[j].string != undefined) {
				s = rowTemplate[j].string;
				re = /#([^#]+)#/g;
				keys = s.match(re);
				for(k=0;k<keys.length;k++) {
					key = keys[k];
					key = key.substring(1,key.length-1);
					s = s.replace(keys[k],data[i][key]);
				}
				table += "<td>" + s + "</td>";
			} else {
				table += "<td>" + data[i][rowTemplate[j].key] + "</td>";
			}
		}
		table += "</tr>";
	}
	table += "</table>";
	
	return table;
}