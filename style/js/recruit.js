/*
 * 招聘选项卡
 * 20141023
 */
$(function(){
        var recruit_menu_li = $('.recruit_menu li');
        $('.recruit_box div:gt(0)').hide();

        recruit_menu_li.click(function(){
            $(this).addClass('selected')
            .siblings().removeClass('selected');

            var recruit_menu_li_index = recruit_menu_li.index(this);
            $('.recruit_box div').eq(recruit_menu_li_index).show()
            .siblings().hide();
            }).hover(function(){
                $(this).addClass('hover');
                },function(){
                $(this).removeClass('hover');	
                });
        }); 

/**
 * 适用于所有版本
 * @activeXString: 自定义属性
 */
function createXHR() {
    if (typeof  XMLHttpRequest != "undefined") {
        return new XMLHttpRequest();//检测是否支持原生
    } else if (typeof ActiveXObject != "undefined") {
        if (typeof arguments.callee.activeXString != "string") {
            var version = ["MSXML2.XMLHttp.6.0", "MSXML2.XMLHttp.3.0",
                "MSXML2.XMLHttp"],
                i, len;
            for (i = 0, len = version.length; i < len; i++) {
                try {
                    new ActiveXObject(version[i]);
                    arguments.callee.activeXString = version[i];
                    break;
                } catch (ex) {

                }
            }
        }
    } else {
        throw new Error("No XHR object available.");
    }
    return new ActiveXObject(arguments.callee.activeXString);
}


 var marketSumPage,  //总页数
    marketThisStart,//当前开始记录
    marketNextStart,//下页开始记录 
    marketPreStart,//上页开始记录 
    marketThisPage,//当前页 
    marketThisTotle,//总条数
    marketDisplay = 7;//

//文职
var civilianSumPage,
    civilianThisStart,
    civilianNextSTart,
    civilianPreStart,
    civilianThisPage,
    civilianThisTotle,
    civilianDisplay=7;

//子分司
var subordinateSumPage,
    schoolThisStart,
    subordinateNextSTart,
    subordinatePreStart,
    subordinateThisPage,
    subordinateThisTotle,
    subordinateDisplay=7;
//校园招骋
var schoolSumPage,
    schoolThisStart,
    schoolNextSTart,
    schoolPreStart,
    schoolThisPage,
    schoolThisTotle,
    schoolDisplay=7;



/*******************************************************
 * Request this server data.
 * 营销岗位
 * 20141028 Tuesday.
 *
 ********************************************************************/ 
function Request (url, disId, start, marketDisplay, channel, page){

    /**
     * 发送请求,接收服务器数据
     **/ 
    this.send = function(url, disId, start, marketDisplay, channel, page)
    {
        //Ajax configuration
        var xhr_market = createXHR();
        xhr_market.onreadystatechange = function () {
            if (xhr_market.readyState == 4) {
                if ((xhr_market.status >= 200 && xhr_market.status < 300)
                        || xhr_market.status == 304) {
                    //console.log(xhr_market.responseText);
                } else {
                    console.log("Request was unsuccessful : " + xhr_market.status);
                }
            }
        };
        
        url += "&c="+channel+"&s="+start+"&d="+marketDisplay+"&p="+page;
        console.log(url);
        xhr_market.open("get", url, false);
        xhr_market.send(null);

        if ((xhr_market.status >= 200 && xhr_market.status < 300) 
                || xhr_market.status == 304) 
        {
            //JSON数据转换
            var market_obj = JSON.parse(xhr_market.responseText);
            //内容位置获取
            var market_tbody = document.getElementById(disId); 
            //删除之前的内容
            var childs = market_tbody.childNodes;
            if(childs.length > 1)
                for(var i = childs.length - 1; i >=0; i--){
                    //console.log(childs[i].nodeName);
                    market_tbody.removeChild(childs[i]);
                }
            //输出信息
            console.log(market_obj);
            //分页信息
            var pageStr = market_obj[0];
            marketThisPage =  pageStr.thisPage;
            marketThisTotle = pageStr.totle;

            var pageCalculate = this.pageCalculate(pageStr.thisPage
                    ,pageStr.totle, marketDisplay);
            //列表数据
            var market_obj = market_obj[1];
            //console.log(market_obj);

            for(var i = 0, len = market_obj.length; i<len; i++){
                //console.log(market_obj[i]);
                var trElement = document.createElement("tr");
                if(i == 0)trElement.setAttribute("class", "border_none");

                for(var j = 0, len2 = market_obj[i].length; j<len2; j++){
                    if(market_obj[i][j] == "") continue;
                    if(j == 1) continue;//ID

                    var tdElement = document.createElement("td");
                    var strElement;
                    var strText;

                    if(j == 5)//人数
                        strText = market_obj[i][j] + "名"; 
                    else
                        strText = market_obj[i][j];

                    if(j == 3 || j==4){ //岗位
                        strElement = document.createElement("a");
                        strElement.setAttribute("href"
                                , "/dynamic/?a=recruit&m=marketDis&id="
                                +market_obj[i][1]+"&selected=1");
                        strElement.appendChild(document.createTextNode(strText));
                        //strElement = document.createTextNode(strText);
                    }else
                        strElement = document.createTextNode(strText);

                    tdElement.appendChild(strElement);//add to "td"
                    trElement.appendChild( tdElement );//add to "tr"   
                    //console.log(market_obj[i][j]);         
                }
                //内容添加至ID
                market_tbody.appendChild(trElement);    
            }    

        } else {
            console.log("Request was unsuccessful: " + xhr_market.status);
        }//End if
    }//End send()


    /**************************************************************
     * 计算分页
     *  @marketThisPage: 当前页, 
     *  @totle:总页数,
     *  @marketDisplay: 每页显示多少条
     *
     *  @return:
     *      下页开始条数
     **/
    this.pageCalculate = function (marketThisPage, totle, marketDisplay){
        //计算总页数
        marketSumPage = Math.ceil(totle/marketDisplay);
        //marketThisStart = (marketThisPage-1) * marketDisplay;
        marketNextStart = (marketThisPage-1) * marketDisplay + marketDisplay;
        marketPreStart = (marketThisPage-1) * marketDisplay - marketDisplay;

        //window.location.href; //获取当前页及参数
        //var suffix = window.location.href.substr(window.location.href.indexOf("?")+1); 
        //var pageThis = suffix.substr(suffix.indexOf("p")).substr(2);
        //console.log(marketNextStart);

        document.getElementById("market_this").innerHTML = marketThisPage;
        document.getElementById("market_sum").innerHTML = marketSumPage;
    }

}

//添加下一页事件 
Request.nextPage = (function () {  
        var list = document.getElementById("market_next");
        EventUtil.addHandler(list, "click", function (event) {
            //event = EventUtil.getEvent(event);
            //var target = EventUtil.getTarget(event);
            console.log("object_next ");
            if(marketThisPage < marketSumPage)
            new Request().send("/dynamic/?a=recruit&m=market", "market_tbody"
                , marketNextStart, marketDisplay, 35, marketThisPage+1);


            //this.send("/dynamic/?a=recruit&m=market", "market_tbody", this.marketNextStart, this.marketDisplay, 35, this.marketThisPage+1);
            else console.log("the end page!");
            });
        })();

//添加上一页
Request.prePage = (function () { 
        var list = document.getElementById("market_pre");
        EventUtil.addHandler(list, "click", function (event) {
            console.log("object_pre");
            if(marketThisPage > 1)
            //this.send("/dynamic/?a=recruit&m=market", "market_tbody", marketPreStart
                //,marketDisplay, 35, marketThisPage-1);
            new Request().send("/dynamic/?a=recruit&m=market", "market_tbody"
                , marketPreStart, marketDisplay, 35, marketThisPage-1);

            else console.log("the end page!");
            });
        })();

//go to some page.
Request.goPage = (function () { 
        var list = document.getElementById("market_go");
        EventUtil.addHandler(list, "click", function (event) {
            var goValue = document.getElementById("market_input").value;     
            goValue = parseInt(goValue);
            var goStart = (goValue-1) * marketDisplay

            if( !isNaN(goValue) && typeof goValue == "number" && goValue >=1 
                && goValue <= marketSumPage )   
            new Request().send("/dynamic/?a=recruit&m=market", "market_tbody", goStart
                ,marketDisplay, 35, goValue);

            console.log(goValue);

            });
        })();

var market = new Request().send("/dynamic/?a=recruit&m=market"
        , "market_tbody", 0, marketDisplay, 34, 1);



/*******************************************************
 * Request this server data.
 * 文职岗位  
 * 20141028 Tuesday.
 *
 ********************************************************************/ 
function RequestCivilian (url, disId, start, civilianDisplay, channel, page){

    /**
     * 发送请求,接收服务器数据
     **/ 
    this.send = function(url, disId, start, civilianDisplay, channel, page)
    {
        //Ajax configuration
        var xhr_civilian = createXHR();
        xhr_civilian.onreadystatechange = function () {
            if (xhr_civilian.readyState == 4) {
                if ((xhr_civilian.status >= 200 && xhr_civilian.status < 300)
                        || xhr_civilian.status == 304) {
                    //console.log(xhr_civilian.responseText);
                } else {
                    console.log("Request was unsuccessful : " + xhr_civilian.status);
                }
            }
        };
        url += "&c="+channel+"&s="+start+"&d="+civilianDisplay+"&p="+page;
        xhr_civilian.open("get", url, false);
        xhr_civilian.send(null);

        if ((xhr_civilian.status >= 200 && xhr_civilian.status < 300) 
                || xhr_civilian.status == 304) {
            //JSON数据转换
            var civilian_obj = JSON.parse(xhr_civilian.responseText);
            //内容位置获取
            var civilian_tbody = document.getElementById(disId); 
            //删除之前的内容
            var childs = civilian_tbody.childNodes;
            if(childs.length > 1)
                for(var i = childs.length - 1; i >=0; i--){
                    //console.log(childs[i].nodeName);
                    civilian_tbody.removeChild(childs[i]);
                }
            //输出信息
            console.log(civilian_obj);
            //分页信息
            var pageStr = civilian_obj[0];
            civilianThisPage =  pageStr.thisPage;
            console.log(civilianThisPage);
            civilianThisTotle = pageStr.totle;

            var pageCalculate = this.pageCalculate(pageStr.thisPage
                    ,pageStr.totle, civilianDisplay);
            //列表数据
            var civilian_obj = civilian_obj[1];

            for(var i = 0, len = civilian_obj.length; i<len; i++){
                //console.log(civilian_obj[i]);
                var trElement = document.createElement("tr");
                if(i == 0)trElement.setAttribute("class", "border_none");

                for(var j = 0, len2 = civilian_obj[i].length; j<len2; j++){
                    if(civilian_obj[i][j] == "") continue;
                    if(j == 1) continue;//ID

                    var tdElement = document.createElement("td");
                    var strElement;
                    var strText;

                    if(j == 5)//人数
                        strText = civilian_obj[i][j] + "名"; 
                    else
                        strText = civilian_obj[i][j];

                    if(j == 3 || j==4){ //岗位
                        strElement = document.createElement("a");
                        strElement.setAttribute("href"
                                , "/dynamic/?a=recruit&m=marketDis&id="
                                +civilian_obj[i][1]+"&selected=2");
                        strElement.appendChild(document.createTextNode(strText));
                        //strElement = document.createTextNode(strText);
                    }else
                        strElement = document.createTextNode(strText);

                    tdElement.appendChild(strElement);//add to "td"
                    trElement.appendChild( tdElement );//add to "tr"   
                    //console.log(civilian_obj[i][j]);         
                }
                //内容添加至ID
                civilian_tbody.appendChild(trElement);    
            }    

        } else {
            console.log("Request was unsuccessful: " + xhr_civilian.status);
        }//End if
    }//End send()


    /**************************************************************
     * 计算分页
     *  @civilianThisPage: 当前页, 
     *  @totle:总页数,
     *  @civilianDisplay: 每页显示多少条
     *  @return:
     *      下页开始条数
     **/
    this.pageCalculate = function (civilianThisPage, totle, civilianDisplay){
        //计算总页数
        civilianSumPage = Math.ceil(totle/civilianDisplay);
        //civilianThisStart = (civilianThisPage-1) * civilianDisplay;
        civilianNextStart = (civilianThisPage-1) * civilianDisplay + civilianDisplay;
        civilianPreStart = (civilianThisPage-1) * civilianDisplay - civilianDisplay;

        //window.location.href; //获取当前页及参数
        //var suffix = window.location.href.substr(window.location.href.indexOf("?")+1); 
        //var pageThis = suffix.substr(suffix.indexOf("p")).substr(2);
        //console.log(civilianNextStart);

        document.getElementById("civilian_this").innerHTML = civilianThisPage;
        document.getElementById("civilian_sum").innerHTML = civilianSumPage;
    }

}

//添加下一页事件 
RequestCivilian.nextPageCivilian = (function () {  
        var list = document.getElementById("civilian_next");
        EventUtil.addHandler(list, "click", function (event) {
            //event = EventUtil.getEvent(event);
            //var target = EventUtil.getTarget(event);
            console.log("Civilian next ");
            if(civilianThisPage < civilianSumPage)
            new RequestCivilian().send("/dynamic/?a=recruit&m=market", "civilian_tbody"
                , civilianNextStart, civilianDisplay, 35, civilianThisPage+1);
            else console.log("the end page!");
            });
        })();

//添加上一页
RequestCivilian.prePageCivilian = (function () { 
        var list = document.getElementById("civilian_pre");
        EventUtil.addHandler(list, "click", function (event) {
            console.log("Civilian pre");
            if(civilianThisPage > 1)
            //this.send("/dynamic/?a=recruit&m=civilian", "civilian_tbody"
            //, civilianPreStart
                //,civilianDisplay, 35, civilianThisPage-1);
            new RequestCivilian().send("/dynamic/?a=recruit&m=market", "civilian_tbody"
                , civilianPreStart, civilianDisplay, 35, civilianThisPage-1);

            else console.log("the end page!");
            });
        })();

//go to some page.
RequestCivilian.goPageCivilian = (function () { 
        var list = document.getElementById("civilian_go");
        EventUtil.addHandler(list, "click", function (event) {
            var goValue = document.getElementById("civilian_input").value;     
            goValue = parseInt(goValue);
            var goStart = (goValue-1) * civilianDisplay
            if( !isNaN(goValue) && typeof goValue == "number" && goValue >=1 
                && goValue <= civilianSumPage )   
            new RequestCivilian().send("/dynamic/?a=recruit&m=market", "civilian_tbody"
                , goStart  ,civilianDisplay, 35, goValue);

            console.log(goValue);

            });
        })();

new RequestCivilian().send("/dynamic/?a=recruit&m=market"
        , "civilian_tbody", 0, civilianDisplay, 35, 1);


/*******************************************************
 * Request this server data.
 * 子公司 岗位  
 * 20141028 Tuesday.
 *
 **************************************************************/ 
function RequestSubordinate (url, disId, start, subordinateDisplay, channel, page){

    /**
     * 发送请求,接收服务器数据
     **/ 
    this.send = function(url, disId, start, subordinateDisplay, channel, page)
    {
        //Ajax configuration
        var xhr_subordinate = createXHR();
        xhr_subordinate.onreadystatechange = function () {
            if (xhr_subordinate.readyState == 4) {
                if ((xhr_subordinate.status >= 200 && xhr_subordinate.status < 300)
                        || xhr_subordinate.status == 304) {
                    //console.log(xhr_subordinate.responseText);
                } else {
                    console.log("Request was unsuccessful : " + xhr_subordinate.status);
                }
            }
        };
        url += "&c="+channel+"&s="+start+"&d="+subordinateDisplay+"&p="+page;
        xhr_subordinate.open("get", url, false);
        xhr_subordinate.send(null);

        if ((xhr_subordinate.status >= 200 && xhr_subordinate.status < 300) 
                || xhr_subordinate.status == 304) {
            //JSON数据转换
            var subordinate_obj = JSON.parse(xhr_subordinate.responseText);
            //内容位置获取
            var subordinate_tbody = document.getElementById(disId); 
            //删除之前的内容
            var childs = subordinate_tbody.childNodes;
            if(childs.length > 1)
                for(var i = childs.length - 1; i >=0; i--){
                    //console.log(childs[i].nodeName);
                    subordinate_tbody.removeChild(childs[i]);
                }
            //输出信息
            console.log(subordinate_obj);
            //分页信息
            var pageStr = subordinate_obj[0];
            subordinateThisPage =  pageStr.thisPage;
            subordinateThisTotle = pageStr.totle;

            var pageCalculate = this.pageCalculate(pageStr.thisPage
                    ,pageStr.totle, subordinateDisplay);
            //列表数据
            var subordinate_obj = subordinate_obj[1];
            //console.log(subordinate_obj);

            for(var i = 0, len = subordinate_obj.length; i<len; i++){
                //console.log(subordinate_obj[i]);
                var trElement = document.createElement("tr");
                if(i == 0)trElement.setAttribute("class", "border_none");

                for(var j = 0, len2 = subordinate_obj[i].length; j<len2; j++){
                    if(subordinate_obj[i][j] == "") continue;
                    if(j == 1 || j ==5  || j==7) continue;//ID
                    var tdElement = document.createElement("td");
                    var strElement;
                    var strText;

                    if(j == 5)//人数
                        strText = subordinate_obj[i][j] + "名"; 
                    else
                        strText = subordinate_obj[i][j];

                    if(j == 3 || j==4){ //岗位
                        strElement = document.createElement("a");
                        strElement.setAttribute("href"
                                , "/dynamic/?a=recruit&m=marketDis&id="
                                +subordinate_obj[i][1]+"&selected=3");
                        strElement.appendChild(document.createTextNode(strText));
                        //strElement = document.createTextNode(strText);
                    }else
                        strElement = document.createTextNode(strText);

                    tdElement.appendChild(strElement);//add to "td"
                    trElement.appendChild( tdElement );//add to "tr"   
                    //console.log(subordinate_obj[i][j]);         
                }
                //内容添加至ID
                subordinate_tbody.appendChild(trElement);    
            }    

        } else {
            console.log("Request was unsuccessful: " + xhr_subordinate.status);
        }//End if
    }//End send()


    /**************************************************************
     * 计算分页
     *  @subordinateThisPage: 当前页, 
     *  @totle:总页数,
     *  @subordinateDisplay: 每页显示多少条
     *  @return:
     *      下页开始条数
     **/
    this.pageCalculate = function (subordinateThisPage, totle, subordinateDisplay){
        //计算总页数
        subordinateSumPage = Math.ceil(totle/subordinateDisplay);
        subordinateNextStart = (subordinateThisPage-1) * subordinateDisplay 
            + subordinateDisplay;
        subordinatePreStart = (subordinateThisPage-1) * subordinateDisplay 
            - subordinateDisplay;
        document.getElementById("subordinate_this").innerHTML = subordinateThisPage;
        document.getElementById("subordinate_sum").innerHTML = subordinateSumPage;
    }

}

//添加下一页事件 
RequestSubordinate.nextPage = (function () {  
        var listSubordinate = document.getElementById("subordinate_next");
        EventUtil.addHandler(listSubordinate, "click", function (event) {
            //event = EventUtil.getEvent(event);
            //var target = EventUtil.getTarget(event);
            console.log("subordinate_next ");
            if(subordinateThisPage < subordinateSumPage)
                new RequestSubordinate().send("/dynamic/?a=recruit&m=market"
                    , "subordinate_tbody" ,subordinateNextStart
                    , subordinateDisplay, 36, subordinateThisPage+1);
            else console.log("the end page!");
            });
        })();

//添加上一页
RequestSubordinate.prePage = (function () { 
        var list = document.getElementById("subordinate_pre");
        EventUtil.addHandler(list, "click", function (event) {
            console.log("subordinate_pre");
            if(subordinateThisPage > 1)
                new RequestSubordinate().send("/dynamic/?a=recruit&m=market"
                , "subordinate_tbody", subordinatePreStart, subordinateDisplay
                , 36, subordinateThisPage-1);

            else console.log("the end page!");
            });
        })();

//go to some page.
RequestSubordinate.goPage = (function () { 
        var list = document.getElementById("subordinate_go");
        EventUtil.addHandler(list, "click", function (event) {
            var goValue = document.getElementById("subordinate_input").value;     
            goValue = parseInt(goValue);
            var goStart = (goValue-1) * subordinateDisplay
            if( !isNaN(goValue) && typeof goValue == "number" && goValue >=1 
                && goValue <= subordinateSumPage )   
                new RequestSubordinate().send("/dynamic/?a=recruit&m=market"
                    , "subordinate_tbody", goStart
                    , subordinateDisplay, 36, goValue);
            console.log(goValue);
            });
        })();

new RequestSubordinate().send("/dynamic/?a=recruit&m=market"
        , "subordinate_tbody", 0, subordinateDisplay, 36, 1);


/*******************************************************
 * Request this server data.
 * 校园招骋 岗位  
 * 20141028 Tuesday.
 *
 **************************************************************/ 
function RequestSchool (url, disId, start, schoolDisplay, channel, page){

    /**
     * 发送请求,接收服务器数据
     **/ 
    this.send = function(url, disId, start, schoolDisplay, channel, page)
    {
        //Ajax configuration
        var xhr_school = createXHR();
        xhr_school.onreadystatechange = function () {
            if (xhr_school.readyState == 4) {
                if ((xhr_school.status >= 200 && xhr_school.status < 300)
                        || xhr_school.status == 304) {
                    //console.log(xhr_school.responseText);
                } else {
                    console.log("Request was unsuccessful : " + xhr_school.status);
                }
            }
        };
        url += "&c="+channel+"&s="+start+"&d="+schoolDisplay+"&p="+page;
        xhr_school.open("get", url, false);
        xhr_school.send(null);

        if ((xhr_school.status >= 200 && xhr_school.status < 300) 
                || xhr_school.status == 304) {
            //JSON数据转换
            var school_obj = JSON.parse(xhr_school.responseText);
            //内容位置获取
            var school_tbody = document.getElementById(disId); 
            //删除之前的内容
            var childs = school_tbody.childNodes;
            if(childs.length > 1)
                for(var i = childs.length - 1; i >=0; i--){
                    //console.log(childs[i].nodeName);
                    school_tbody.removeChild(childs[i]);
                }
            //输出信息
            console.log(school_obj);
            //分页信息
            var pageStr = school_obj[0];
            schoolThisPage =  pageStr.thisPage;
            schoolThisTotle = pageStr.totle;

            var pageCalculate = this.pageCalculate(pageStr.thisPage
                    ,pageStr.totle, schoolDisplay);
            //列表数据
            var school_obj = school_obj[1];
            //console.log(school_obj);

            for(var i = 0, len = school_obj.length; i<len; i++){
                //console.log(school_obj[i]);
                var trElement = document.createElement("tr");
                if(i == 0)trElement.setAttribute("class", "border_none");

                for(var j = 0, len2 = school_obj[i].length; j<len2; j++){
                    if(school_obj[i][j] == "") continue;
                    if(j == 1) continue;//ID

                    var tdElement = document.createElement("td");
                    var strElement;
                    var strText;

                    if(j == 5)//人数
                        strText = school_obj[i][j] + "名"; 
                    else
                        strText = school_obj[i][j];

                    if(j == 3 || j==4){ //岗位
                        strElement = document.createElement("a");
                        strElement.setAttribute("href"
                                , "/dynamic/?a=recruit&m=marketDis&id="
                                +school_obj[i][1]+"&selected=4");
                        strElement.appendChild(document.createTextNode(strText));
                        //strElement = document.createTextNode(strText);
                    }else
                        strElement = document.createTextNode(strText);

                    tdElement.appendChild(strElement);//add to "td"
                    trElement.appendChild( tdElement );//add to "tr"   
                    //console.log(school_obj[i][j]);         
                }
                //内容添加至ID
                school_tbody.appendChild(trElement);    
            }    

        } else {
            console.log("Request was unsuccessful: " + xhr_school.status);
        }//End if
    }//End send()


    /**************************************************************
     * 计算分页
     *  @schoolThisPage: 当前页, 
     *  @totle:总页数,
     *  @schoolDisplay: 每页显示多少条
     *  @return:
     *      下页开始条数
     **/
    this.pageCalculate = function (schoolThisPage, totle, schoolDisplay){
        //计算总页数
        schoolSumPage = Math.ceil(totle/schoolDisplay);
        schoolNextStart = (schoolThisPage-1) * schoolDisplay 
            + schoolDisplay;
        schoolPreStart = (schoolThisPage-1) * schoolDisplay 
            - schoolDisplay;
        document.getElementById("school_this").innerHTML = schoolThisPage;
        document.getElementById("school_sum").innerHTML = schoolSumPage;
    }

}

//添加下一页事件 
RequestSchool.nextPage = (function () {  
        var listSchool = document.getElementById("school_next");
        EventUtil.addHandler(listSchool, "click", function (event) {
            //event = EventUtil.getEvent(event);
            //var target = EventUtil.getTarget(event);
            console.log("school_next ");
            if(schoolThisPage < schoolSumPage)
                new RequestSchool().send("/dynamic/?a=recruit&m=market"
                    , "school_tbody" ,schoolNextStart
                    , schoolDisplay, 35, schoolThisPage+1);
            else console.log("the end page!");
            });
        })();

//添加上一页
RequestSchool.prePage = (function () { 
        var list = document.getElementById("school_pre");
        EventUtil.addHandler(list, "click", function (event) {
            console.log("school_pre");
            if(schoolThisPage > 1)
                new RequestSchool().send("/dynamic/?a=recruit&m=market"
                , "school_tbody", schoolPreStart, schoolDisplay
                , 35, schoolThisPage-1);

            else console.log("the end page!");
            });
        })();

//go to some page.
RequestSchool.goPage = (function () { 
        var list = document.getElementById("school_go");
        EventUtil.addHandler(list, "click", function (event) {
            var goValue = document.getElementById("school_input").value;     
            goValue = parseInt(goValue);
            var goStart = (goValue-1) * schoolDisplay
            if( !isNaN(goValue) && typeof goValue == "number" && goValue >=1 
                && goValue <= schoolSumPage )   
                new RequestSchool().send("/dynamic/?a=recruit&m=market"
                    , "school_tbody", goStart
                    , schoolDisplay, 35, goValue);
            console.log(goValue);
            });
        })();

new RequestSchool().send("/dynamic/?a=recruit&m=market"
        , "school_tbody", 0, schoolDisplay, 37, 1);


