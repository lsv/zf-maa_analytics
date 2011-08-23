
// usage: log('inside coolFunc', this, arguments);
// paulirish.com/2009/log-a-lightweight-wrapper-for-consolelog/
window.log = function(){
  log.history = log.history || [];   // store logs to an array for reference
  log.history.push(arguments);
  arguments.callee = arguments.callee.caller;  
  if(this.console) console.log( Array.prototype.slice.call(arguments) );
};
// make it safe to use console.log always
(function(b){function c(){}for(var d="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","),a;a=d.pop();)b[a]=b[a]||c})(window.console=window.console||{});

function array_search (needle, haystack) {
    var key = '';
    if (haystack && typeof haystack === 'object' && haystack.change_key_case) {return haystack.search(needle);}
    if (typeof needle === 'object' && needle.exec) {
        for (key in haystack) {
            if (needle.test(haystack[key])) {return key;}
        }
        return false;
    }
 
    for (key in haystack) {
        if (haystack[key] == needle) {return key;}
    }
 
    return false;
}

/**
 * @todo Save cookies
 */
var Uncheckboxes = {
    _checks: [],
    _filterId: 'aside#filter input.',
    
    click: function(type) {
        var _this = this;
        $(_this._filterId + type).each(function() {
            $el = $(this);
            var checked = $el.attr('checked') || false;
            
            switch (type) {
                case 'venues':
                    role = 'data-sports';
                    uncheckedElement = 'sports';
                    break;
                case 'sports':
                    role = 'data-venues';
                    uncheckedElement = 'venues';
                    break;
                default:return ;
            }
            
            ids = _this._getIds($el, role);
            _this._uncheck(ids, uncheckedElement, checked);
            
        })
    },
    
    _uncheck: function(ids, elem, checked) {
        _this = this;
        $.each(ids, function(index, el) {
            $(_this._filterId + elem + '[value="' + el + '"]').each(function() {
                var $elem = $(this);
                if (checked != false) {
                    $elem.attr('disabled', false).next().removeClass('nouse');
                    s = array_search($elem.attr('id'), _this._checks);
                    if (s !== false) {
                        $elem.attr('checked', true);
                        _this._checks.splice(s,1);
                    }
                } else {
                    if ($elem.is(':checked')) {
                        _this._checks.push($elem.attr('id'));
                    }
                    $elem.attr('checked', false).attr('disabled', true).next().addClass('nouse');
                }
            })
        });
    },
    
    _getIds: function(el, role) {
        var ids = el.attr(role);
        if (ids == undefined || ids == '') return [];
        if (ids.indexOf(',')) {
            return ids.split(',');
        }
        return [ids];
    }
}