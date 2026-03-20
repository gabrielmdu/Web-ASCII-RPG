import { ref, watch } from 'vue';
import { debounce } from 'lodash-es';

export interface GameFilter {
  search: string;
  sort: string;
  public: boolean;
  asc: boolean;
  page: number;
}

const getDefaultFilters = (): GameFilter => {
  return {
    search: '',
    sort: 'created_at',
    public: false,
    asc: true,
    page: 1,
  };
};

type FetchCallback = (filters: GameFilter) => void;

export const useGameFilters = (fetchCallback: FetchCallback) => {
  const filters = ref<GameFilter>(getDefaultFilters());

  const resetFilters = function () {
    filters.value = getDefaultFilters();
  };

  const debouncedFetch = debounce(() => {
    fetchCallback(filters.value);
  }, 300);

  // if these filters change, go back to page 1
  watch(
    () => [filters.value.search, filters.value.public],
    () => {
      filters.value.page = 1;
    },
  );

  watch(filters, () => debouncedFetch(), { deep: true });

  return { filters, resetFilters };
};
