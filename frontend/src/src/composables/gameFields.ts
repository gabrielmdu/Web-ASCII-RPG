import { ref, watch } from 'vue';
import { debounce } from 'lodash-es';

export interface GameFilter {
  search: string;
  sort: string;
  public: boolean;
  asc: boolean;
  page: number;
}

type FetchCallback = (filters: GameFilter) => void;

export const useGameFilters = (fetchCallback: FetchCallback) => {
  const filters = ref({
    search: '',
    sort: 'created_at',
    public: false,
    asc: true,
    page: 1,
  });

  const debouncedFetch = debounce(() => {
    fetchCallback(filters.value);
  }, 300);

  watch(filters, () => debouncedFetch(), { deep: true });

  return { filters };
};
