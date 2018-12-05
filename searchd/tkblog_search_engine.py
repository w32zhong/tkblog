from whoosh.index import *
from whoosh.fields import *
from whoosh.qparser import *
from jieba.analyse import ChineseAnalyzer
import os
import sys

def get_script_path():
    return os.path.dirname(os.path.realpath(sys.argv[0]))

g_blog_path = get_script_path() + '/../blog'
g_index_dir="index"
g_table = dict()
g_schema = Schema(blog_id=TEXT(stored=True), 
                post=TEXT(stored=True, analyzer=ChineseAnalyzer()))

def whoosh_clear_index():
    try:
        os.stat(g_index_dir)
    except:
        os.mkdir(g_index_dir)
    create_in(g_index_dir, g_schema)
    g_table.clear()
    return 'index: cleared.'

def whoosh_index_file(path):
    fname = os.path.basename(path)
    fname_prefix = fname[0:2]
    blog_id_str = '0'
    description = 'index: '
    if fname_prefix == 'co':
        description += 'comment ' + path + ' '
        arr = fname.split('-')
        blog_id_str = arr[6]
    elif fname_prefix == '20':
        description += 'blog post ' + path + ' '
        arr = fname.split('-')
        blog_id_str = arr[5][:-4]

        # record we have indexed it
        if blog_id_str in g_table:
            g_table[blog_id_str] += 1
            description += 'is already indexed, conflicts: '
            description += str(g_table[blog_id_str])
            # log if there is any conflict
            with open("conflicts.log", "a") as text_file:
                text_file.write(description + '\n')
            return description
        else:
            g_table[blog_id_str] = 1

    if blog_id_str == 0:
        description += 'cannot parse filename correctly: ' + path
        return description

    # index it
    ix = open_dir(g_index_dir);
    writer = ix.writer()
    content = open(path, encoding='utf-8').read()
    writer.add_document(blog_id=blog_id_str, post=content)
    writer.commit()
    description += 'is successfully indexed.'
    return description

def whoosh_index_under(blog_path):
    print('index path: ' + blog_path)
    for path, dirs, files in os.walk(blog_path):
        dirname = os.path.basename(path)
        if dirname == 'src' or dirname == 'draft':
            continue # skip src directory
        elif dirname == 'blog': # do not index comments
            for f in files:
                f_location = path + '/' + f
                print('index: ' + f_location)
                whoosh_index_file(f_location)

def whoosh_index_all():
    whoosh_index_under(g_blog_path)

def whoosh_search(query, page):
    ix = open_dir(g_index_dir);
    res = dict()
    with ix.searcher() as se:
        q = QueryParser("post", ix.schema).parse(query)
        #print(query)
        results = se.search_page(q, page, pagelen=6)
        res['page_now'] = results.pagenum 
        res['pagecount'] = results.pagecount
        res_list = list()
        for hit in results:
            res_item = dict()
            res_item['highlights'] = hit.highlights('post')
            res_item['blog_id'] = hit['blog_id']
            res_list.append(res_item)
        res['list'] = res_list
    return res

# whoosh_clear_index()
# whoosh_index_file('./2015-08-25-01-25-1074.txt')
# whoosh_index_file('./com-2015-09-8-08-29-1078-2.txt')
# whoosh_index_under(g_blog_path)
# print(whoosh_search('脚本', 1))
# print(whoosh_search('安倍', 1))
