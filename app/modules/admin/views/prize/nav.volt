{{ partial('partial/nav', [
    'title': '奖品设置',
    'navs': [
        ['index', '全部奖品', url('/admin/prize/')],
        ['rate', '发放设置', url('/admin/prize/rate/')],
        ['redpacket', '红包设置', url('/admin/prize/redpacket/')]
    ],
    'current_nav': current_nav is not empty ? current_nav : 'index'
]) }}