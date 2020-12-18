(function(d){var f=function(b){this.tipPosition={x:0,y:0};this.init(b)};f.prototype.init=function(b){function a(a){var c={};c.x=a.pageX;c.y=a.pageY;b.setTooltipPosition(c)}function m(a,g,e){var f=function(a,b,c,g){return Math.sqrt((c-a)*(c-a)+(g-b)*(g-b))};if(e)b.showTooltip(e,g);else if(c.plotOptions.series.lines.show&&!0===c.tooltipOptions.lines.track){var m=-1,p;d.each(b.getData(),function(a,h){for(var e,d=0,l=-1,k=1;k<h.data.length;k++)h.data[k-1][0]<=g.x&&h.data[k][0]>=g.x&&(d=k-1,l=k);if(-1===
l)b.hideTooltip();else{var k=h.data[d][0],t=h.data[d][1],v=h.data[l][0],w=h.data[l][1],q;q=g.x;var z=g.y,x,y;y=!1;x=t-w;e=v-k;q=Math.abs(x*q+e*z+(k*w-t*v))/Math.sqrt(x*x+e*e);q<c.tooltipOptions.lines.threshold&&(d=f(k,t,g.x,g.y)<f(g.x,g.y,v,w)?d:l,d={datapoint:[g.x,t+(g.x-k)/(v-k)*(w-t)],dataIndex:d,series:h,seriesIndex:a},-1===m||q<m)&&(m=q,p=d)}});-1!==m?b.showTooltip(p,g):b.hideTooltip()}else b.hideTooltip()}var c=this,f=d.plot.plugins.length;this.plotPlugins=[];if(f)for(var l=0;l<f;l++)this.plotPlugins.push(d.plot.plugins[l].name);
b.hooks.bindEvents.push(function(b,g){c.plotOptions=b.getOptions();!1!==c.plotOptions.tooltip&&"undefined"!==typeof c.plotOptions.tooltip&&(c.tooltipOptions=c.plotOptions.tooltipOpts,c.tooltipOptions.$compat?(c.wfunc="width",c.hfunc="height"):(c.wfunc="innerWidth",c.hfunc="innerHeight"),c.getDomElement(),d(b.getPlaceholder()).bind("plothover",m),d(g).bind("mousemove",a))});b.hooks.shutdown.push(function(b,c){d(b.getPlaceholder()).unbind("plothover",m);d(c).unbind("mousemove",a)});b.setTooltipPosition=
function(a){var b=c.getDomElement(),e=b.outerWidth()+c.tooltipOptions.shifts.x,b=b.outerHeight()+c.tooltipOptions.shifts.y;a.x-d(window).scrollLeft()>d(window)[c.wfunc]()-e&&(a.x-=e);a.y-d(window).scrollTop()>d(window)[c.hfunc]()-b&&(a.y-=b);c.tipPosition.x=a.x;c.tipPosition.y=a.y};b.showTooltip=function(a,d){var e=c.getDomElement(),m=c.stringFormat(c.tooltipOptions.content,a);e.html(m);b.setTooltipPosition({x:d.pageX,y:d.pageY});e.css({left:c.tipPosition.x+c.tooltipOptions.shifts.x,top:c.tipPosition.y+
c.tooltipOptions.shifts.y}).show();if("function"===typeof c.tooltipOptions.onHover)c.tooltipOptions.onHover(a,e)};b.hideTooltip=function(){c.getDomElement().hide().html("")}};f.prototype.getDomElement=function(){var b=d("#"+this.tooltipOptions.id);0===b.length&&(b=d("<div />").attr("id",this.tooltipOptions.id),b.appendTo("body").hide().css({position:"absolute"}),this.tooltipOptions.defaultTheme&&b.css({background:"#fff","z-index":"1040",padding:"0.4em 0.6em","border-radius":"0.4em","font-size":"0.8em",
border:"1px solid #b1b1b1",display:"none","white-space":"nowrap"}));return b};f.prototype.stringFormat=function(b,a){var d=/%p\.{0,1}(\d{0,})/,c=/%s/,f=/%lx/,l=/%ly/,h=/%x\.{0,1}(\d{0,})/,g=/%y\.{0,1}(\d{0,})/,e,n,r,p;"undefined"!==typeof a.series.threshold?(e=a.datapoint[0],n=a.datapoint[1],r=a.datapoint[2]):"undefined"!==typeof a.series.lines&&a.series.lines.steps?(e=a.series.datapoints.points[2*a.dataIndex],n=a.series.datapoints.points[2*a.dataIndex+1],r=""):(e=a.series.data[a.dataIndex][0],n=
a.series.data[a.dataIndex][1],r=a.series.data[a.dataIndex][2]);null===a.series.label&&a.series.originSeries&&(a.series.label=a.series.originSeries.label);"function"===typeof b&&(b=b(a.series.label,e,n,a,this.tooltipOptions.fmatter));"undefined"!==typeof a.series.percent?p=a.series.percent:"undefined"!==typeof a.series.percents&&(p=a.series.percents[a.dataIndex]);"number"===typeof p&&(b=this.adjustValPrecision(d,b,p));b=b.toString();b="undefined"!==typeof a.series.label?b.replace(c,a.series.label):
b.replace(c,"");b=this.hasAxisLabel("xaxis",a)?b.replace(f,a.series.xaxis.options.axisLabel):b.replace(f,"");b=this.hasAxisLabel("yaxis",a)?b.replace(l,a.series.yaxis.options.axisLabel):b.replace(l,"");this.isTimeMode("xaxis",a)&&this.isXDateFormat(a)&&(b=b.replace(h,this.timestampToDate(e,this.tooltipOptions.xDateFormat,a.series.xaxis.options)));this.isTimeMode("yaxis",a)&&this.isYDateFormat(a)&&(b=b.replace(g,this.timestampToDate(n,this.tooltipOptions.yDateFormat,a.series.yaxis.options)));"number"===
typeof e&&(b=this.adjustValPrecision(h,b,e));"number"===typeof n&&(b=this.adjustValPrecision(g,b,n));"undefined"!==typeof a.series.xaxis.ticks&&(d=this.hasRotatedXAxisTicks(a)?"rotatedTicks":"ticks",c=a.dataIndex+a.seriesIndex,a.series.xaxis[d].length>c&&!this.isTimeMode("xaxis",a)&&(this.isCategoriesMode("xaxis",a)?a.series.xaxis[d][c].label:a.series.xaxis[d][c].v)===e&&(b=b.replace(h,a.series.xaxis[d][c].label)));if("undefined"!==typeof a.series.yaxis.ticks)for(var u in a.series.yaxis.ticks)a.series.yaxis.ticks.hasOwnProperty(u)&&
(this.isCategoriesMode("yaxis",a)?a.series.yaxis.ticks[u].label:a.series.yaxis.ticks[u].v)===n&&(b=b.replace(g,a.series.yaxis.ticks[u].label));"undefined"!==typeof a.series.xaxis.tickFormatter&&(b=b.replace("%x",a.series.xaxis.tickFormatter(e,a.series.xaxis).replace(/\$/g,"$$")));"undefined"!==typeof a.series.yaxis.tickFormatter&&(b=b.replace("%y",a.series.yaxis.tickFormatter(n,a.series.yaxis).replace(/\$/g,"$$")));r&&(b=b.replace("%ct",r));return b};f.prototype.isTimeMode=function(b,a){return"undefined"!==
typeof a.series[b].options.mode&&"time"===a.series[b].options.mode};f.prototype.isXDateFormat=function(b){return"undefined"!==typeof this.tooltipOptions.xDateFormat&&null!==this.tooltipOptions.xDateFormat};f.prototype.isYDateFormat=function(b){return"undefined"!==typeof this.tooltipOptions.yDateFormat&&null!==this.tooltipOptions.yDateFormat};f.prototype.isCategoriesMode=function(b,a){return"undefined"!==typeof a.series[b].options.mode&&"categories"===a.series[b].options.mode};f.prototype.timestampToDate=
function(b,a,f){b=d.plot.dateGenerator(b,f);return d.plot.formatDate(b,a,this.tooltipOptions.monthNames,this.tooltipOptions.dayNames)};f.prototype.adjustValPrecision=function(b,a,d){var c;null!==a.match(b)&&""!==RegExp.$1&&(c=RegExp.$1,d=d.toFixed(c),a=a.replace(b,d));return a};f.prototype.hasAxisLabel=function(b,a){return-1!==d.inArray(this.plotPlugins,"axisLabels")&&"undefined"!==typeof a.series[b].options.axisLabel&&0<a.series[b].options.axisLabel.length};f.prototype.hasRotatedXAxisTicks=function(b){return-1!==
d.inArray(this.plotPlugins,"tickRotor")&&"undefined"!==typeof b.series.xaxis.rotatedTicks};d.plot.plugins.push({init:function(b){new f(b)},options:{tooltip:!1,tooltipOpts:{id:"flotTip",content:"%s | X: %x | Y: %y",xDateFormat:null,yDateFormat:null,monthNames:null,dayNames:null,shifts:{x:10,y:20},defaultTheme:!0,lines:{track:!1,threshold:.05},onHover:function(b,a){},$compat:!1}},name:"tooltip",version:"0.8.4"})})(jQuery);