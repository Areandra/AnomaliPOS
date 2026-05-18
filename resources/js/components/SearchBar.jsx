import { Search } from 'lucide-react'
import { useState } from 'react'

export function SearchBar({
  searchItem,
  searchKeyField,
  onSelect,
  theme = 'dark',
}) {
  const isDark = theme === 'dark'
  const [searchKey, setSearchKey] = useState('')
  const [isOpen, setIsOpen] = useState(false)

  const results = searchKey
    ? searchItem.filter((item) =>
        String(item[searchKeyField]).toLowerCase().includes(searchKey.toLowerCase())
      )
    : []

  return (
    <div className="w-full relative group">
      {/* Search Input Container */}
      <div className="relative flex items-center">
        <div className={`absolute left-4 ${isDark ? 'text-gray-500' : 'text-gray-400'}`}>
          <Search size={16} />
        </div>
        <input
          value={searchKey}
          onChange={(e) => {
            setSearchKey(e.target.value)
            setIsOpen(true)
          }}
          onFocus={() => setIsOpen(true)}
          type="text"
          placeholder="Search tables..."
          className={`
            w-full pl-11 pr-4 py-2.5 rounded-full border text-sm font-medium transition-all duration-300 outline-none
            ${
              isDark
                ? 'bg-slate-950 border-white/5 text-gray-200 placeholder-gray-600 focus:border-indigo-500/50 focus:ring-4 focus:ring-indigo-500/10'
                : 'bg-gray-100 border-gray-200 text-gray-800 placeholder-gray-400 focus:bg-white focus:border-orange-500/50 focus:ring-4 focus:ring-orange-500/10'
            }
          `}
        />
      </div>

      {/* Results Dropdown */}
      {isOpen && results.length > 0 && (
        <div
          className={`
            absolute top-full left-0 right-0 mt-2 z-100 rounded-3xl border shadow-2xl overflow-hidden backdrop-blur-xl transition-all animate-in fade-in slide-in-from-top-2
            ${isDark ? 'bg-slate-900/95 border-white/10' : 'bg-white/95 border-gray-200'}
          `}
        >
          <div className="max-h-60 overflow-y-auto scrollbar-hide">
            {results.map((item, idx) => (
              <button
                key={idx}
                onClick={() => {
                  onSelect?.(item)
                  setSearchKey('')
                  setIsOpen(false)
                }}
                className={`
                  w-full text-left px-5 py-3 text-xs font-black uppercase tracking-widest flex items-center justify-between group/item
                  ${isDark ? 'text-gray-300 hover:bg-white/5' : 'text-gray-600 hover:bg-gray-50'}
                  ${idx !== results.length - 1 ? (isDark ? 'border-b border-white/5' : 'border-b border-gray-100') : ''}
                `}
              >
                <span>Table {item[searchKeyField]}</span>
                <span
                  className={`
                  text-[9px] px-2 py-0.5 rounded-md transition-all
                  ${isDark ? 'bg-indigo-500/10 text-indigo-400 group-hover/item:bg-indigo-500 group-hover/item:text-white' : 'bg-orange-100 text-orange-600 group-hover/item:bg-orange-600 group-hover/item:text-white'}
                `}
                >
                  VIEW
                </span>
              </button>
            ))}
          </div>
        </div>
      )}

      {/* Overlay for closing dropdown when clicking outside */}
      {isOpen && results.length > 0 && (
        <div className="fixed inset-0 z-[-1]" onClick={() => setIsOpen(false)} />
      )}
    </div>
  )
}
