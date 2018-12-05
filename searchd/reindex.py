import tkblog_search_engine

print('clear index...')
tkblog_search_engine.whoosh_clear_index()

print('index all...')
tkblog_search_engine.whoosh_index_all()
