class AutomationElement {
    static fromJson(json, container, events = {} ) {
        var trigger;

        if (events.onclick == null) {
            events.onclick = function() {};
        }

        if (events.onhover == null) {
            events.onhover = function() {};
        }

        if (events.onadd == null) {
            events.onadd = function() {};
        }

        if (events.onaddyes == null) {
            events.onaddyes = function() {};
        }

        if (events.onaddno == null) {
            events.onaddno = function() {};
        }

        var list = [];
        var i;
        var e;
        // Initiate objects
        for (i = 0; i < json.length; i += 1) {
            e = json[i];
            if (e.type == 'ElementTrigger') {
                list[e.id] = new ElementTrigger(e);

                list[e.id].draw(360, 80, container);
                list[e.id].setPosition(1, 0);
                trigger = list[e.id];

                trigger.onclick(events.onclick);
                trigger.onhover(events.onhover);
                trigger.onadd(events.onadd);
                trigger.setCustomFunction('validate', events.validate);

                // add to events bucket
                trigger.events['onclick'] = events.onclick;
                trigger.events['onhover'] = events.onhover;
                trigger.events['onadd'] = events.onadd;
                trigger.events['onaddyes'] = events.onaddyes;
                trigger.events['onaddno'] = events.onaddno;

                trigger.functions['validate'] = events.validate;
            } else if (e.type == 'ElementAction') {
                list[e.id] = new ElementAction(e);
            } else if (e.type == 'ElementWait') {
                list[e.id] = new ElementWait(e);
            } else if (e.type == 'ElementCondition') {
                list[e.id] = new ElementCondition(e);
            } else if (e.type == 'ElementOperation') {
                list[e.id] = new ElementOperation(e);
            }
        }

        // Assignment
        for (i = 0; i < json.length; i += 1) {
            e = json[i];
            var o = list[ e.id ];

            if (o.isCondition()) {
                if (e.childYes != null && e.childYes !== '') {
                    o.appendYes(list[e.childYes]);
                }

                if (e.childNo != null && e.childNo !== '') {
                    o.appendNo(list[e.childNo]);
                }

                //o.onaddyes(events.onaddyes);
                //o.onaddno(events.onaddno);
            } else {
                if (e.child != null && e.child !== '') {
                    o.append(list[e.child]);
                }

                //o.onadd(events.onadd);
            }

            //o.onclick(events.onclick);
            //o.onhover(events.onhover);
        }
        trigger.organize();
        
        trigger.browse(function(e){
            e.validate();
        });

        return trigger;
    }

    constructor(params = {}) {
        this.DefaultRound = 5;
        this.DefaultRectWith = 220;
        this.DefaultRectHeight = 32;
        this.DefaultFill = '#aaa';
        this.DefaultPointSize = 4;
        this.DefaultVerticalSpace = 30;
        this.DefaultActionCircleSize = 16;
        this.MinShift = 0.5;

        this.title = params.title;
        this.setId(params.id);

        this.parent = null;
        this.selected = false;

        this.events = {};
        this.functions = {};

        if (params.options == null) {
            params.options = {};
        }
        this.options = params.options;
    }

    getType() {
        return this.constructor.name;
    }

    getTitle() {
        return this.title;
    }

    setOptions(hash) {
        this.options = hash;
    }

    getOptions() {
        return this.options;
    }

    setTitle(title) {
        this.title = title;
        var textElement = document.getElementById(this.getId() + '_title');
        textElement.innerHTML = this.title;
        this.crop(textElement);
    }

    crop(text) {
        var maxSize = this.DefaultRectWith - 30;
        while (text.getComputedTextLength() > maxSize) {
            text.textContent = text.textContent.slice(0, -4).trim() + "...";
        }
    };

    copyEventsFromTrigger() {
        for (var i = 0; i < Object.keys(this.getTrigger().events).length ; i += 1) {
            var event = Object.keys(this.getTrigger().events)[i];
            var func = this.getTrigger().events[event];
            try {
                this[event](func);
                console.log('event added: ' + event);
            } catch (err) {
                console.log('warning: cannot assign event "' + event + '" to element "' + this.title + '"');
            }

        }
    }

    setCustomFunction(name, func) {
        this[name] = function() {
            func(this);
        }
    }

    copyCustomFunctionsFromTrigger() {
        for (var i = 0; i < Object.keys(this.getTrigger().functions).length ; i += 1) {
            var name = Object.keys(this.getTrigger().functions)[i];
            var func = this.getTrigger().functions[name];

            try {
                this.setCustomFunction(name, func);
                console.log('custom function added: ' + name);
            } catch (err) {
                console.log('warning: cannot assign custom function "' + event + '" to element "' + this.title + '"');
            }

        }
    }

    // Custom Events
    onclick(callback) {
        // cover the A (link) element only
        var me = this;
        var selector = '#' + this.getId() + '_textlink';

        $(document).off('click', selector);
        $(document).on('click', selector, function() {
            callback(me);
        });
    }

    onhover(callback) {
        // cover the outbound rect
        var me = this;
        var selector = '#' + this.getId();

        $(document).off('mouseover', selector);
        $(document).on('mouseover', selector, function() {
            callback(me);
        });
    }

    select() {
        this.getTrigger().browse(function(element){
            element.deselect();
        });

        var rect = document.getElementById(this.getId());
        var currentCls = rect.getAttribute('class');
        rect.setAttribute('class', currentCls + ' selected');
        this.selected = true;
    }

    deselect() {
        this.selected = false;
        var rect = document.getElementById(this.getId());
        var clsArray = rect.getAttribute('class').trim().split(/\s+/);

        if (clsArray.indexOf('selected') >= 0) {
            clsArray.splice(clsArray.indexOf('selected') , 1);
        }

        rect.setAttribute('class', clsArray.join(" "));
    }

    /*
    run(speed) {
        this.speed += speed;
        alert(`${this.name} runs with speed ${this.speed}.`);
    }

    stop() {
      this.speed = 0;
      alert(`${this.name} stands still.`);
    }
    */

    drawPlus(id, cx, cy, ctype, container) {
        var g = document.createElementNS("http://www.w3.org/2000/svg", "g");
        var circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
        var a = document.createElementNS("http://www.w3.org/2000/svg", 'a');
        var text = document.createElementNS("http://www.w3.org/2000/svg", 'text');

        var box = document.createElementNS("http://www.w3.org/2000/svg", 'g');
        box.setAttribute('transform', 'translate(' + cx + ',' + (cy + this.DefaultActionCircleSize/2) + ')');

        circle.setAttribute('id', id);
        circle.setAttribute('_cx', cx);
        circle.setAttribute('_cy', cy + this.DefaultActionCircleSize/2);
        circle.setAttribute('ctype', ctype);
        circle.setAttribute('r', this.DefaultActionCircleSize);
        circle.setAttribute('style', 'fill:#fff;stroke:rgb(101, 117, 138);stroke-width:1');

        a.setAttribute('id', id + '-iconlink');
        a.setAttribute('xlink:href', '#');
        a.setAttribute('style',  'cursor:pointer');

        text.setAttribute('x', 0);
        text.setAttribute('y', 2);
        text.setAttribute('font-size', '16px');
        text.setAttribute('alignment-baseline', 'middle');
        text.setAttribute('text-anchor', 'middle');

        if (ctype == 'plus') {
            text.innerHTML = '+';
            text.setAttribute('font-size', '24px');
            text.setAttribute('x', 0);
        text.setAttribute('y', 3);
        } else if (ctype == 'yes') {
            text.innerHTML = 'Y';
        } else {
            text.innerHTML = 'N';
        }

        text.setAttribute('font-family', 'Arial');
        text.setAttribute('fill', '#aaa');

        container.appendChild(g);
        g.appendChild(box);
        box.appendChild(circle);
        box.appendChild(a);
        a.appendChild(text);
    }

    drawConnector(highId, lowId, container, strokeType = 'solid') {
        var g = document.createElementNS("http://www.w3.org/2000/svg", "g");
        g.setAttribute('id', lowId + '_connector');
        container.appendChild(g);

        var high = document.getElementById(highId);
        var low = document.getElementById(lowId);
        var point1;
        var point2;

        if (high.tagName == 'rect') {
            point1 = this.drawCircle('circle1', parseInt(high.getAttribute('_x')) + this.DefaultRectWith/2, parseInt(high.getAttribute('_y')) + this.DefaultRectHeight, g, false);
        } else {
            point1 = this.drawCircle('circle1', parseInt(high.getAttribute('_cx')), parseInt(high.getAttribute('_cy')) + this.DefaultActionCircleSize, g, true);
        }

        if (low.tagName == 'rect') {
            point2 = this.drawCircle('circle2', parseInt(low.getAttribute('_x')) + this.DefaultRectWith/2, parseInt(low.getAttribute('_y')), g, false);
        } else {
            point2 = this.drawCircle('circle2', parseInt(low.getAttribute('_cx')), parseInt(low.getAttribute('_cy')) - this.DefaultActionCircleSize, g, true);
        }

        this.drawPath(point1, point2, g, strokeType);
    }

    drawAction(left, top, container, type = 'action') {
        var g = document.createElementNS("http://www.w3.org/2000/svg", "g");
        g.setAttribute('id', this.id + '_group');
        g.setAttribute('class', type);
        container.appendChild(g);

        var box = document.createElementNS("http://www.w3.org/2000/svg", "g");
        box.setAttribute('transform', 'translate(' + left + ',' + top + ')');
        
        var rect = document.createElementNS("http://www.w3.org/2000/svg", "rect");   // Create a <button> element
        rect.setAttribute('id', this.id);
        rect.setAttribute('_x', left);
        rect.setAttribute('_y', top);
        rect.setAttribute('rx', this.DefaultRound);
        rect.setAttribute('ry', this.DefaultRound);
        rect.setAttribute('width', this.DefaultRectWith);
        rect.setAttribute('height', this.DefaultRectHeight);

        var a = document.createElementNS("http://www.w3.org/2000/svg", 'a');
        var text = document.createElementNS("http://www.w3.org/2000/svg", 'text');
        text.setAttribute('x', this.DefaultRectWith/2);
        text.setAttribute('y', this.DefaultRectHeight/2);
        text.setAttribute('alignment-baseline', 'middle');
        text.setAttribute('text-anchor', 'middle');

        var text1 = document.createElementNS("http://www.w3.org/2000/svg", 'tspan');

        if (type == 'trigger') {
            rect.setAttribute('class', 'element trigger');
            text1.innerHTML = '&#xf144;&nbsp;&nbsp;';
        } else if (type == 'wait') {
            rect.setAttribute('class', 'element wait');
            text1.innerHTML = '&#xf1da;&nbsp;&nbsp;';
        } else if (type == 'action') {
            rect.setAttribute('class', 'element action');
            text1.innerHTML = '&#xf0e0;&nbsp;&nbsp;';
        } else if (type == 'operation') {
            rect.setAttribute('class', 'element operation');
            text1.innerHTML = '&#xf2bd;&nbsp;&nbsp;';
        }

        text1.setAttribute('font-family', 'FontAwesome');
        text1.setAttribute('fill', '#fff');
        text1.setAttribute('font-size', '15px');
        text1.setAttribute('alignment-baseline', 'middle');
        text1.setAttribute('text-anchor', 'middle');

        
        a.setAttribute('id', this.id + '_textlink');
        a.setAttribute('xlink:href', 'https://stackoverflow.com/questions/34968082/how-to-add-a-link-inside-an-svg-circle');
        a.setAttribute('style',  'cursor:pointer');

        var text2 = document.createElementNS("http://www.w3.org/2000/svg", 'tspan');
        text2.setAttribute('id', this.getId() + '_title');
        text2.setAttribute('font-family', 'Arial');
        text2.setAttribute('fill', '#fff');
        text2.setAttribute('font-size', '12px');
        text2.setAttribute('alignment-baseline', 'middle');
        text2.setAttribute('text-anchor', 'middle');
        // text2.innerHTML = this.title;

        g.appendChild(box);
        box.appendChild(rect)
        box.appendChild(a);
        a.appendChild(text);
        text.appendChild(text1);
        text.appendChild(text2);

        var plusId = this.id + "-plus";
        this.drawPlus(plusId, left + this.DefaultRectWith/2, top + this.DefaultRectHeight + this.DefaultVerticalSpace, 'plus', g);
        this.drawConnector(this.id, plusId, g);
        this.drawNotice(this.id + '-notice', left, top, g);

        // also crop text
        this.setTitle(this.title);
    }

    drawCircle(id, cx, cy, container, zeroPoint = false) {
        var g = document.createElementNS("http://www.w3.org/2000/svg", "g");
        container.appendChild(g);

        var circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
        circle.setAttribute('id', id);
        circle.setAttribute('cx', cx);
        circle.setAttribute('cy', cy);
        circle.setAttribute('_cx', cx);
        circle.setAttribute('_cy', cy);
        if (zeroPoint) {
            circle.setAttribute('r', 0);
        } else {
            circle.setAttribute('r', this.DefaultPointSize);
        }

        circle.setAttribute('fill', this.DefaultFill);
        g.appendChild(circle);
        return circle;
    }

    drawPath(point1, point2, container, strokeType) {
        var path = document.createElementNS("http://www.w3.org/2000/svg", "path");
        var p1 = point2.getAttribute('_cx') + "," + (parseInt(point2.getAttribute('_cy')) - parseInt(point2.getAttribute('r')));
        var p2 = point2.getAttribute('_cx')  + "," + (parseInt(point1.getAttribute('_cy')) + parseInt(point1.getAttribute('r')));
        var p3 = point1.getAttribute('_cx')  + "," + (parseInt(point2.getAttribute('_cy')) - parseInt(point2.getAttribute('r')));
        var p4 = point1.getAttribute('_cx')  + "," + (parseInt(point1.getAttribute('_cy')) + parseInt(point1.getAttribute('r')));

        path.setAttribute('d', "M" + p1  + " C" + p2 + " " + p3  + " " + p4 );
        if (strokeType == 'solid') {
            path.setAttribute('stroke-width', 1);
            path.setAttribute('stroke', '#ccc');
        } else {
            path.setAttribute('stroke-width', 2);
            path.setAttribute('stroke-dasharray', 5);
            path.setAttribute('stroke', '#aaa');
        }
        
        path.setAttribute('fill', 'transparent');
        
        container.appendChild(path);
        return path;
    }

    setId(id) {
        if (id == null) {
            var randomId = Math.floor(Math.random() * 999999999) + 100000000;
            this.id = randomId;
        } else {
            this.id = id;
        }
    }

    getId() {
        return this.id;
    }

    clear() {
        var container = document.getElementById(this.id + '_group');
        this.parent = null;

        if (container != null) {
            container.parentNode.removeChild(container);
        }
    }

    getContainer() {
        return document.getElementById(this.getId() + '_group');
    }

    getConnector() {
        return document.getElementById( this.id + '_connector' );
    }

    removeConnector() {
        this.getConnector().parentNode.removeChild(this.getConnector());
    }

    insert(element) {
        // ngắt kết nối cha-con
        var orphan = this.child;

        // Insert element mới
        this.append(element);

        // element mới trở thành cha mới
        if (orphan != null) {
            element.append(orphan);
        }
    }

    isCondition() {
        return false;
    }

    findNoParent() {
        if (this.parent == null) {
            return null;
        }

        if (!this.parent.isCondition() || this.parent.childNo == null) {
            return this.parent.findNoParent();
        } else

        if (this.parent.childNo.getId() != this.getId()) {
            return this.parent.findNoParent();
        }

        return this.parent;
    }

    setPosition(x, y) {
        this.x = x;
        this.y = y;
    }

    findLeftMostSibling(levelAdjust = 0) {
        var minX = this.x;
        var leftMostSibling = null;
        var me = this;
        this.getTrigger().browse(function(element) {
            if ((me.y == element.y || me.y == element.y + levelAdjust) && me.getId() != element.getId()) {
                if (element.x <= minX) {
                    minX = element.x;
                    leftMostSibling = element;
                }
            }
        });

        return leftMostSibling;
    }

    findRightMostSibling(levelAdjust = 0) {
        var minX = null;
        var rightMostSibling = null;
        var me = this;
        this.getTrigger().browse(function(element) {
            if ((me.y == element.y + levelAdjust) && me.getId() != element.getId()) {
                if (element.x >= me.x) {
                    if (minX == null) {
                        minX = element.x;
                        rightMostSibling = element;
                    } else if (minX >= element.x) {
                        minX = element.x;
                        rightMostSibling = element;
                    }
                }
            }
        });

        return rightMostSibling;
    }

    getTrigger() {
        if (this.parent == null) {
            return this;
        }

        return this.parent.getTrigger();
    }

    hasChildren() {
        return (this.child == null) ? false : true;
    }

    getDistance(element) {
        return Math.abs(this.x - element.x);
    }

    reposition() {
        var right = this.findRightMostSibling(-0.5); // tìm element nằm cao hơn 0.5
        while (right != null && this.getDistance(right) < 1.5) {
            right.shiftToRight();
            right = this.findRightMostSibling(-0.5);
        }

        right = this.findRightMostSibling();
        while (right != null && this.getDistance(right) < 1.5) {
            right.shiftToRight();
            right = this.findRightMostSibling();
        }

        right = this.findRightMostSibling(0.5); // tìm element nằm cao hơn 0.5
        var safe = (right != null && right.isCondition()) ? 2.0 : 1.0;
        while (right != null && this.getDistance(right) < safe) {
            console.log('Con nhieu day: ' + this.getDistance(right));
            right.shiftToRight();
            right = this.findRightMostSibling(0.5);
            safe = (right.isCondition()) ? 2.0 : 1.0;
        }
    }

    remove() {
        if (this.parent != null) {
            if (this.parent.isCondition()) {
                if (this.parent.childYes && this.parent.childYes.getId() == this.getId()) {
                    this.parent.childYes = null;
                } else {
                    this.parent.childNo = null;
                }
            } else {
                this.parent.child = null;
            }    
        }

        this.clear();
    }

    drawNotice(id, left, top, container) {
        var g = document.createElementNS("http://www.w3.org/2000/svg", "g");
        var circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
        var a = document.createElementNS("http://www.w3.org/2000/svg", 'a');
        var text = document.createElementNS("http://www.w3.org/2000/svg", 'text');
        var title = document.createElementNS("http://www.w3.org/2000/svg", 'title');

        var cx = left + this.DefaultRectWith;
        var cy = top;

        g.setAttribute('id', id);
        g.style.visibility = 'hidden';

        circle.setAttribute('cx', cx);
        circle.setAttribute('cy', cy);
        circle.setAttribute('r', 10);
        circle.setAttribute('style', 'fill:#bb1e1e;stroke:#eee;stroke-width:1');

        a.setAttribute('xlink:href', '#');
        //a.setAttribute('style',  'cursor:pointer');

        text.setAttribute('x', cx - 2);
        text.setAttribute('y', cy + 5);
        text.setAttribute('font-size', '12px');
        text.setAttribute('font-family', 'FontAwesome');
        text.setAttribute('fill', '#fefefe');
        text.innerHTML = '&#xf12a;';

        container.appendChild(g);
        g.appendChild(circle);
        g.appendChild(a);
        a.appendChild(text);
        g.appendChild(title);
    }

    getGroupId() {
        return this.getId() + '_group';
    }

    getNoticeId() {
        return this.getId() + '-notice';
    }

    showNotice(message) {
        var title = document.getElementById(this.getGroupId()).querySelector('title');
        title.innerHTML = message;
        var notice = document.getElementById(this.getNoticeId());
        notice.style.visibility = 'visible';
    }

    hideNotice() {
        var notice = document.getElementById(this.getNoticeId());
        if (notice != null) {
            notice.style.visibility = 'hidden';
        }
    }
}

class ElementAction extends AutomationElement {

    constructor(params = {}) {
        super(params);

        this.child = null;
    }

    getPlusId() {
        return this.id + '-plus';
    }

    draw(top, left, container = null) {
        this.drawAction(top, left, container);
    }

    append(element) {
        // break the relationship with the child
        if (this.child != null) {
            this.child.clear();
        }

        // clear the canvas only, its relationship is retained
        element.clear();

        var plus = document.getElementById(this.getPlusId());

        element.draw(parseInt(plus.getAttribute('_cx')) - this.DefaultRectWith/2, parseInt(plus.getAttribute('_cy')) + this.DefaultActionCircleSize/2 + this.DefaultVerticalSpace, this.getContainer());
        this.drawConnector(this.getPlusId(), element.id, element.getContainer());

        this.child = element;
        element.parent = this;

        // IMPORTANT: setPosition must go first, before reappending childrend
        element.setPosition(this.x, this.y + 1);

        // reappend children
        element.reappendChild();

        // copy events from trigger buckets
        element.copyEventsFromTrigger();

        // copy custom functions from trigger buckets
        element.copyCustomFunctionsFromTrigger();
    }

    reappendChild() {
        if (this.child != null) {
            var c = this.child;
            c.clear();
            this.append(c);
        }
    }

    shiftToRight() {
        this.parent.shiftToRight();
    }

    onadd(callback) {
        var selector = '#' + this.getPlusId() + '-iconlink';
        var me = this;
        $(document).off('click', selector);
        $(document).on('click', selector, function() {
            callback(me);
        });
    }
}

class ElementTrigger extends ElementAction {
    draw(top, left, container = null) {
        this.drawAction(top, left, container, 'trigger');
    }

    browse(callback) {
        this.traverse(this, callback);
    }

    get(id) {
        var item = null;
        this.browse(function(e) {
            if (e.getId() == id) {
                item = e;
            }
        });

        return item;
    }

    undo() {
        return true;
    }

    redo() {
        return true;
    }

    traverse(element, callback) {
        callback(element);

        if (element.isCondition()) {
            if (element.childYes != null) {
                this.traverse(element.childYes, callback);
            }

            if (element.childNo != null) {
                this.traverse(element.childNo, callback);
            }
        } else {
            if (element.child != null) {
                this.traverse(element.child, callback);
            }
        }
    }

    getLeaves(callback) {
        this.traverse(this, function(element) {
            if (!element.hasChildren()) {
                callback(element);
            }
        });
    }

    organize() {
        // organize condition elements
        this.browse(function(element) {
            if (element.getType() == 'ElementCondition') {
                element.reposition();
            }
        });

        // organize leaves
        this.getLeaves(function(element) {
            element.reposition();
        });

        // shift tree to right if needed
        var minX = 0;
        this.browse(function(element) {
            if (element.x < minX) {
                minX = element.x;
            }
        });  

        if (minX < 0) {
            this.redrawTree(Math.abs(minX));
        }
    }

    toJson() {
        var json = [];
        this.browse(function(element){
            var item = { title: element.title, id: element.id, type: element.constructor.name, options: element.options };

            if (element.isCondition()) {
                if (element.childYes != null) {
                    item.childYes = element.childYes.id;
                } else {
                    item.childYes = null;
                }

                if (element.childNo != null) {
                    item.childNo = element.childNo.id;
                } else {
                    item.childNo = null;
                }
            } else {
                if (element.child != null) {
                    item.child = element.child.id;
                } else {
                    item.child = null;
                }
            }

            json.push(item);
        });

        return json;
    }

    getSelected() {
        var selected = null;
        this.browse(function(e) {
            if (e.selected) {
                selected = e;
            }
        });

        return selected;
    }

    redrawTree(step) {
        // get trigger RECT
        var rect = document.getElementById(this.getId());
        var newX = parseInt(rect.getAttribute('_x')) + (step * this.DefaultRectWith);
        var newY = parseInt(rect.getAttribute('_y'));
        
        // trick, get true/original container
        var container = this.getContainer().parentNode;

        // clear HTML
        this.clear();

        console.log(newX);
        console.log(newY);
        console.log(container);

        // redraw
        this.draw(newX, newY, container);
        this.setPosition(this.x + step, this.y);
        this.reappendChild();
    }
}

class ElementWait extends ElementAction {
    draw(top, left, container = null) {
        this.drawAction(top, left, container, 'wait');
    }
}

class ElementOperation extends ElementAction {
    draw(top, left, container = null) {
        this.drawAction(top, left, container, 'operation');
    }
}

class ElementCondition extends AutomationElement {
    constructor(...args) {
        super(...args);

        this.shrink = false;
        this.shift = 0;
        this.expand = 0;
    }

    isCondition() {
        return true;
    }

    hasChildYes() {
        return (this.childYes == null) ? false : true;
    }

    hasChildNo() {
        return (this.childNo == null) ? false : true;
    }

    drawCondition(left, top, container) {
        // A little bit confusing here
        var g = document.createElementNS("http://www.w3.org/2000/svg", "g");
        g.setAttribute('id', this.id + '_group');
        g.setAttribute('class', 'condition');
        container.appendChild(g);

        var shifted = this.shift * this.DefaultRectWith;

        var box = document.createElementNS("http://www.w3.org/2000/svg", "g");
        box.setAttribute('transform', 'translate(' + (left  + shifted) + ',' + top + ')');

        var rect = document.createElementNS("http://www.w3.org/2000/svg", "rect");   // Create a <button> element
        rect.setAttribute('id', this.id);
        rect.setAttribute('_x', left + shifted);
        rect.setAttribute('_y', top);
        rect.setAttribute('rx', this.DefaultRound);
        rect.setAttribute('ry', this.DefaultRound);
        rect.setAttribute('width', this.DefaultRectWith);
        rect.setAttribute('height', this.DefaultRectHeight);
        rect.setAttribute('class', 'element condition');

        var a = document.createElementNS("http://www.w3.org/2000/svg", 'a');

        var text = document.createElementNS("http://www.w3.org/2000/svg", 'text');
        text.setAttribute('x', this.DefaultRectWith/2);
        text.setAttribute('y', this.DefaultRectHeight/2);
        text.setAttribute('alignment-baseline', 'middle');
        text.setAttribute('text-anchor', 'middle');

        var text1 = document.createElementNS("http://www.w3.org/2000/svg", 'tspan');
        text1.innerHTML = '&#xf059;&nbsp;&nbsp;';
        text1.setAttribute('font-family', 'FontAwesome');
        text1.setAttribute('fill', '#fff');
        text1.setAttribute('font-size', '15px');
        text1.setAttribute('alignment-baseline', 'middle');
        text1.setAttribute('text-anchor', 'middle');

        
        a.setAttribute('id', this.getId() + '_textlink');
        a.setAttribute('xlink:href', 'https://stackoverflow.com/questions/34968082/how-to-add-a-link-inside-an-svg-circle');
        a.setAttribute('style',  'cursor:pointer');

        var text2 = document.createElementNS("http://www.w3.org/2000/svg", 'tspan');
        //text2.innerHTML = this.title;
        text2.setAttribute('id', this.getId() + '_title');
        text2.setAttribute('font-family', 'Arial');
        text2.setAttribute('fill', '#fff');
        text2.setAttribute('font-size', '12px');
        text2.setAttribute('alignment-baseline', 'middle');
        text2.setAttribute('text-anchor', 'middle');

        g.appendChild(box);
        box.appendChild(rect)
        box.appendChild(a);
        a.appendChild(text);
        text.appendChild(text1);
        text.appendChild(text2);

        this.drawConditionPlus(rect.getAttribute('_x'), rect.getAttribute('_y'));

        this.drawNotice(this.id + '-notice', left, top, g);

        // also crop text
        this.setTitle(this.title);
    }

    drawConditionPlus(rectX, rectY) {
        var adjust = 0;
        if (this.shrink == true) {
            adjust += 1 * this.DefaultRectWith / 2;
        }

        var plusYesLabel = this.id + "-plusYesLabel";
        this.drawPlus(
            plusYesLabel,
            parseInt(rectX) - this.DefaultRectWith/2 + adjust,
            parseInt(rectY) + this.DefaultRectHeight + 1.5*this.DefaultVerticalSpace,
            'yes',
            this.getContainer()
        );

        var plusYes = this.id + "-plusYes";
        this.drawPlus(
            plusYes,
            parseInt(rectX) - this.DefaultRectWith/2 + adjust,
            parseInt(rectY) + this.DefaultRectHeight + 2*this.DefaultVerticalSpace + this.DefaultRectHeight - this.DefaultActionCircleSize/2,
            'plus',
            this.getContainer()
        );
        this.drawConnector(plusYesLabel, plusYes, this.getContainer(), 'dashed');
        this.drawConnector(this.id, plusYesLabel, this.getContainer(), 'dashed');

        var expanded = this.expand * this.DefaultRectWith;

        var plusNoLabel = this.id + "-plusNoLabel";
        this.drawPlus(
            plusNoLabel,
            parseInt(rectX) + this.DefaultRectWith*1.5 + expanded,
            parseInt(rectY) + this.DefaultRectHeight + 1.5*this.DefaultVerticalSpace,
            'no',
            this.getContainer()
        );

        var plusNo = this.id + "-plusNo";
        this.drawPlus(
            plusNo,
            parseInt(rectX) + this.DefaultRectWith*1.5 + expanded,
            parseInt(rectY) + this.DefaultRectHeight + 2*this.DefaultVerticalSpace + this.DefaultRectHeight - this.DefaultActionCircleSize/2,
            'plus',
            this.getContainer()
        );

        this.drawConnector(plusNoLabel, plusNo,  this.getContainer(), 'dashed');
        this.drawConnector(this.id, plusNoLabel,  this.getContainer(), 'dashed');
    }

    getPlusYesId() {
        return this.id + '-plusYes';
    }

    getPlusNoId() {
        return this.id + '-plusNo';
    }

    draw(top, left, container) {
        this.drawCondition(top, left, container);
    }

    appendYes(element) {
        if (this.childYes != null) {
            this.childYes.clear();
        }

        // clear the canvas only, its relationship is retained
        element.clear();

        var plus = document.getElementById(this.getPlusYesId());

        element.draw(parseInt(plus.getAttribute('_cx')) - this.DefaultRectWith/2, parseInt(plus.getAttribute('_cy')) + this.DefaultActionCircleSize/2 + this.DefaultVerticalSpace, this.getContainer());
        this.drawConnector(plus.id, element.id, element.getContainer(), 'dashed');

        this.childYes = element;
        element.parent = this;

        // IMPORTANT: setPosition must go first before reappending
        element.setPosition(this.x - ((this.shrink) ? 0.5 : 1), this.y + 1.5);
        element.reappendChild();

        element.copyEventsFromTrigger();

        // copy custom functions from trigger buckets
        element.copyCustomFunctionsFromTrigger();
    }

    appendNo(element) {
        if (this.childNo != null) {
            this.childNo.clear();
        }

        // clear the canvas only, its relationship is retained
        element.clear();

        var plus = document.getElementById(this.getPlusNoId());
        element.draw(parseInt(plus.getAttribute('_cx')) - this.DefaultRectWith/2, parseInt(plus.getAttribute('_cy')) + this.DefaultActionCircleSize/2 + this.DefaultVerticalSpace, this.getContainer());
        this.drawConnector(plus.id, element.id, element.getContainer(), 'dashed');

        this.childNo = element;
        element.parent = this;

        // IMPORTANT: setPosition must go first before reappending
        element.setPosition(this.x + 1 + this.expand, this.y + 1.5);
        element.reappendChild();

        element.copyEventsFromTrigger();

        // copy custom functions from trigger buckets
        element.copyCustomFunctionsFromTrigger();
    }

    reappendChild() {
        this.reappendChildren();
    }

    // Notice: reappend to take advantage of the drawing code in the function
    //         it is better to separate association and drawing code
    reappendChildren() {
        if (this.childYes != null) {
            var y = this.childYes;
            y.clear();
            this.appendYes(y);
        }

        if (this.childNo != null) {
            var n = this.childNo;
            n.clear();
            this.appendNo(n);
        }
    }

    shiftToRight() {
        // if not yet shrink, then just shrink
        if (this.shrink == false) {
            this.shrink = true;
            this.parent.reappendChild();
            return true;
        }

        // otherwise, find NoParent
        var noParent = this.findNoParent();

        if (noParent != null) {
            noParent.expand = noParent.expand + this.MinShift;
            noParent.parent.reappendChild();
        }
    }

    insertYes(element) {
        // ngắt kết nối cha-con
        var orphan = this.childYes;

        // Insert element mới
        this.appendYes(element);

        // element mới trở thành cha mới
        if (orphan != null) {
            element.append(orphan);
        }
    }

    insertNo(element) {
        // ngắt kết nối cha-con
        var orphan = this.childNo;

        // Insert element mới
        this.appendNo(element);

        // element mới trở thành cha mới
        if (orphan != null) {
            element.append(orphan);
        }
    }

    hasChildren() {
        return (this.childYes == null && this.childNo == null) ? false : true;
    }

    reposition() {
        var right = this.findRightMostSibling(-0.5); // sâu hơn nửa line
        while (right != null && this.getDistance(right) < 2) {
            right.shiftToRight();
            right = this.findRightMostSibling(-0.5);
        }

        var right = this.findRightMostSibling(-1.0); // sâu hơn 1 line
        while (right != null && this.getDistance(right) < 2) {
            right.shiftToRight();
            right = this.findRightMostSibling(-1.0);
        }

        var right = this.findRightMostSibling(); // ngang hàng
        var safeDistance = 1.5;
        if (right != null) {
            if (right.getType() == 'ElementCondition') {
                safeDistance = 2.5;
            }    
        }
        while (right != null && this.getDistance(right) < safeDistance) {
            right.shiftToRight();
            right = this.findRightMostSibling();

            safeDistance = 1.5;
            if (right != null) {
                if (right.getType() == 'ElementCondition') {
                    safeDistance = 2.5;
                }    
            }
        }

        var right = this.findRightMostSibling(0.5); // cao hơn 0.5 line
        var safeDistance = 2.0;
        while (right != null && this.getDistance(right) < safeDistance) {
            right.shiftToRight();
            right = this.findRightMostSibling(0.5);
        }
    }

    onaddyes(callback) {
        var selector = '#' + this.getPlusYesId() + '-iconlink';
        var me = this;
        $(document).off('click', selector);
        $(document).on('click', selector, function() {
            callback(me);
        });
    }

    onaddno(callback) {
        var selector = '#' + this.getPlusNoId() + '-iconlink';
        var me = this;
        $(document).off('click', selector);
        $(document).on('click', selector, function() {
            callback(me);
        });
    }
}
