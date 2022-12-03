class GroupManager {
    constructor() {
        this.groups = [];
    }

    getOtherGroups(theGroup) {
        var others = [];
        this.groups.forEach(function(group, index) {
            if (index != theGroup.index) {
                others.push(group);
            }
        });

        return others;
    }

    bind(action) {
        var _this = this;

        this.groups.forEach(function(group) {
            var otherGroups = _this.getOtherGroups(group);
            action(group, otherGroups);
        });
    }

    add(group) {
        group = $.extend({}, group, {
            index: this.groups.length,
        });
        this.groups.push(group);
    }
};