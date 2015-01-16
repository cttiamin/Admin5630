/**
 * Created with JetBrains WebStorm.
 * User: Administrator
 * Date: 14-1-10
 * Time: 下午2:01
 * To change this template use File | Settings | File Templates.
 */

//跨browser code, 取消默认动作 ,
var EventUtil = {
    /**
     * 添加事件
     * @param element   元素
     * @param type      事件类型
     * @param handler   匿名函数
     */
    addHandler: function (element, type, handler) {
        if (element.addEventListener) {  //DOM2级
            element.addEventListener(type, handler, false);
        } else if (element.attachEvent) { //ie特有
            element.attachEvent("on" + type, handler);
        } else {    //DOM0级
            element["on" + type] = handler;
        }
    },
    /**
     * 移除事件
     * @param element :
     * @param type : Event type
     * @param handler : The add function name
     */
    removeHandler: function (element, type, handler) {
        if (element.removeEventListene) {
            element.removeEventListener(type, handler, false);
        } else if (element.detachEvent) {
            element.detachEvent("on" + type, handler);
        } else {
            element["on" + type] = null;
        }
    },
    /**
     * 13.3.3 获取事件
     * @param event : 当前event对象
     * @returns {*} : event对象的引用
     */
    getEvent: function (event) {
        return event ? event : window.event;
    },
    /**
     * 13.4.3 鼠标与滚轮事件 => 6.鼠标按钮
     * 将IE(IE8之前)模型下的button属性 转换为 DOM模型下的button属性
     * @param event
     * @returns {*}
     */
    getButton: function (event) {
        if (document.implementation.hasFeature("MouseEvents", "2.0")) { //如果是DOM
            return event.button;
        } else {    //IE8之前,必须规范化
            switch (event.button) {
                case 0:
                case 1:
                case 3:
                case 5:
                case 7:
                    return 0;
                case 2:
                case 6:
                    return 2;
                case 4:
                    return 1;
            }
        }
    },
    /**
     * 13.4.3 鼠标与滚轮事件, 5.相关元素
     * DOM通过event对象的relatedTarget属性提供了相关元素信息
     * 这个属性只对mouseover,mouseout事件有效
     * IE8以前不支持,提供其它属性: fromElement.
     */
    getRelatedTarget: function (event) {
        if (event.relatedTarget) {
            return event.relatedTarget;
        } else if (event.toElement) {
            return event.toElement;
        } else if (event.fromElement) {
            return event.fromElement;
        } else {
            return null;
        }
    },
    /**
     * 13.3.3 获取事件的目标
     * @param event
     * @returns :触发事件的标签对象
     */
    getTarget: function (event) {
        return event.target || event.srcElement;
    },
    /**
     * 13.3.3, 取消事件的默认行为
     * @param event
     */
    preventDefault: function (event) {
        if (event.preventDefault) {
            event.preventDefault();
        } else {
            event.returnValue = false;
        }
    },
    /**
     * 13.3.3 取消冒泡事件, 取消进一个捕获或冒泡
     * @param event
     */
    stopPropagation: function (event) {
        if (event.stopPropagation) {
            event.stopPropagation();
        } else {  //ie
            event.cancelBubble = true;
        }
    },
    /**
     * 13.4.3 鼠标与滚轮事件, 8.鼠标滚轮事件
     * @param event
     * @returns {number} ; 滚轮事件的值, 120/-120
     */
    getWheelDelta: function (event) {
        if (event.wheelDelta) { //if Opera and others
            return (client.engine.opera && client.engine.opera < 9.5 ?
                -event.wheelDelta : event.wheelDelta);
        }else{  // if FireFox,把值取反*40,  --3*40 = 120
            return -event.detail * 40;
        }
    },
    /**
     * 13.4.4 键盘与文本事件 => 2.字符编码
     * 检测charCode是否可用,不支持测用keyCode
     * @param event
     * @returns {*} 按下那个键的所代表字符的ASCII编码
     */
    getCharCode: function(event){
        if(typeof event.charCode == "number"){
            return event.charCode;
        }else{
            return event.keyCode;
        }
    },
    /**
     * * 14.2.2 过滤输入=> 2.操作剪帖
     * 从剪帖板中获得数据
     * @param event :当前对象
     * @returns {string} : 剪贴板中数据
     */
    getClipboardText : function(event){
        var clipboardData = (event.clipboardData || window.clipboardData );
        return  clipboardData.getData("text");
    },
    /**
     * 14.2.2 过滤输入=> 2.操作剪帖
     * @param event : 当前对象
     * @param value : 放入剪贴板的文本
     * @returns {boolean}: success:true, error:false
     */
    setClipboardText: function(event, value){
        if(event.clipboardData){
            return event.clipboardData.setData("text/plain", value);//Safari, Chrome
        }else if(window.clipboardData){
            return window.clipboardData.setData("text", value); //ie
        }
    }

};
