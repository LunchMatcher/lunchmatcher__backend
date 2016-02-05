// JavaScript Document
(function($){
 "use strict";
		
	
	var tableFy = function(){};
	 	  
	
	$.fn.tablefy = function(options){
		var defaults = {
				// These are the defaults.
				url: "",
				limit: 10,
				page : 0,
				REST_url : null,
				REST_params : null,
				
				
		},
		settings = $.extend(defaults, options );	
		
		return this.each(function(){
			settings.elem = $(this);
			
			var table = new tableFy();
			table.init(settings);		
		});
	};
	
	tableFy.prototype = {	
		settings : '',
		init : function(options){					
			this.settings = options;			
			this.generate_pagination();			
		},
		generate_pagination : function(){  
			this.createTable();
			this.getRowResults();			
			this.settings.elem.html(this.table);
		},	
		sl :1,		
		getRowResults : function(){
			var element = this.settings.elem;			
			var self = this;
			var arg = {
				callback : function(resp){  					 
						$.each(resp,function(index,item){
							self.append_row(item);
						});						 
				},
				failure : function(){
					element.html('<div style="width:100%; text-align:center;">No hauls to show</div>');		
				},
			};
			this._sendAjax(arg);
		},
		 
		append_row : function(obj){ 
			var display_fields = this.settings.display_fields;
			var row = '<tr><td>'+(this.sl++)+'</td>';
			console.log(display_fields);
			for(var i in display_fields){
				var val = obj[display_fields[i].field] ? obj[display_fields[i].field] : '';
				if(display_fields[i].prefix)
					val = display_fields[i].prefix+val;
					
					row += '<td>'+val+'</td>';
					
				if(Number(i)+1 == display_fields.length){
					if(this.settings.detail_page){
						row += '<td><span class="badge bg-blue"><a href="'+this.settings.detail_page.url+obj[this.settings.detail_page.handle]+'">View</a></span></td>';		
					}					
					row += '</tr>';	
					this.table.append(row);
				}
			}			
		},
		_sendAjax : function(arg){ 
			var self = this;
			$.ajax({
				type : 'get',
				url : this.settings.REST_url,
				data : this.settings.REST_params,
				success : function(data){  
					if(!data.error){
						if('callback' in arg) arg.callback(data);
					}else{   						
						if('failure' in arg) arg.failure();
					}
				},
			});
		},
		createTable : function(){
			var display_fields = this.settings.display_fields;
			this.table = $('<table class="table table-bordered"></table>');	
			if(display_fields && display_fields.length){
				var th = '<thead><tr><th style="width: 10px;"></th>';
				for(var i in display_fields){
					if(display_fields[i].title)
						th += '<th>'+display_fields[i].title+'</th>';					
					if(Number(i)+1 == display_fields.length){
						if(this.settings.detail_page){
							th += '<th></th>';
						}
						th += '</tr></thead>';
						this.table.append(th);
						//console.log(this.table.html());
					}
				}
			}else{
				throw new Error('display field lists empty!');
			}
			
		},
	};

}(jQuery));		   
